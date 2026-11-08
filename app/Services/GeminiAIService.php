<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
        $this->model   = config('services.gemini.model', 'gemini-2.0-flash');
        $this->baseUrl = "https://generativelanguage.googleapis.com/v1beta/models";
    }

    /**
     * Verifica si el servicio está configurado.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Envía un mensaje a Gemini y retorna la respuesta.
     *
     * @param string $userMessage  Mensaje del usuario
     * @param string $systemContext Contexto del sistema (rol/instrucciones)
     * @param array  $history       Historial de conversación previo [{role, parts}]
     * @return array ['ok' => bool, 'response' => string, 'error' => string|null]
     */
    public function chat(string $userMessage, string $systemContext = '', array $history = []): array
    {
        if (!$this->isConfigured()) {
            return [
                'ok'     => false,
                'error'  => 'El servicio de IA no está configurado. Falta la API Key de Gemini.',
                'response' => null,
            ];
        }

        // Contexto del sistema para Trigestion
        $systemPrompt = $systemContext ?: $this->getDefaultSystemPrompt();

        // Construir contents para Gemini API
        $contents = [];

        // Agregar contexto del sistema como primer mensaje
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $systemPrompt]],
        ];
        $contents[] = [
            'role'  => 'model',
            'parts' => [['text' => 'Entendido. Soy el asistente IA de Trigestion. ¿En qué puedo ayudarte?']],
        ];

        // Agregar historial
        foreach ($history as $msg) {
            $contents[] = [
                'role'  => $msg['role'] ?? 'user',
                'parts' => [['text' => $msg['parts'] ?? $msg['text'] ?? '']],
            ];
        }

        // Agregar mensaje actual del usuario
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $userMessage]],
        ];

        try {
            $url = "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}";

            $response = Http::timeout(30)->post($url, [
                'contents'              => $contents,
                'generationConfig'      => [
                    'temperature'     => 0.7,
                    'maxOutputTokens'  => 1024,
                    'topP'             => 0.95,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT',         'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH',       'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',  'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [
                    'ok'     => false,
                    'error'  => 'Error al comunicarse con Gemini: ' . $response->status(),
                    'response' => null,
                ];
            }

            $data = $response->json();

            // Extraer texto de la respuesta
            $text = $data['candidates'][0]['content']['parts'][0]['text']
                ?? $data['candidates'][0]['content']['parts'][0]['text']
                ?? 'No se pudo generar una respuesta.';

            return [
                'ok'       => true,
                'response' => trim($text),
                'error'    => null,
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Exception', ['message' => $e->getMessage()]);

            return [
                'ok'     => false,
                'error'  => 'Excepción: ' . $e->getMessage(),
                'response' => null,
            ];
        }
    }

    /**
     * Obtiene el prompt del sistema por defecto para Trigestion.
     */
    private function getDefaultSystemPrompt(): string
    {
        return <<<PROMPT
Eres el Asistente IA de Trigestion, un sistema de gestión de agua purificada embotellada.

Tu rol:
- Asistir a administradores y trabajadores con consultas sobre inventario, producción, ventas y devoluciones.
- Proporcionar recomendaciones sobre stock bajo, alertas de insumos, y optimización de producción.
- Responder de forma clara, concisa y profesional en español.
- Usar formato Markdown cuando sea útil (listas, negritas, tablas).

No debes:
- Inventar datos que no se te proporcionen.
- Dar consejos financieros o legales.
- Realizar acciones sobre el sistema, solo informar y recomendar.

Contexto del sistema Trigestion:
- Productos: agua purificada en garrafones 20L, botellas individuales, termos deportivos.
- Producción: lotes de fabricación con materia prima (envases, tapas, etiquetas).
- Inventario: materia prima (insumos) y producto terminado (stock de agua embotellada).
- Ventas: pedidos online de clientes y punto de venta (POS) con soporte de pacas y unidades.
- Devoluciones: control de envases retornables (garrafones que el cliente devuelve vacíos).
- Roles: Administrador (1), Trabajador (2), Cliente (3).
PROMPT;
    }
}
