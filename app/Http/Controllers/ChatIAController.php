<?php

namespace App\Http\Controllers;

use App\Services\GeminiAIService;
use Illuminate\Http\Request;

class ChatIAController extends Controller
{
    /**
     * Procesar mensaje del chat IA.
     */
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message'  => 'required|string|max:2000',
            'history'  => 'nullable|array',
            'context'  => 'nullable|string|max:5000',
        ]);

        $service = new GeminiAIService();

        if (!$service->isConfigured()) {
            return response()->json([
                'ok'     => false,
                'error'  => 'El servicio de IA no está configurado. Contacta al administrador para configurar la API Key de Gemini en el archivo .env (GEMINI_API_KEY).',
            ], 503);
        }

        $result = $service->chat(
            $data['message'],
            $data['context'] ?? '',
            $data['history'] ?? []
        );

        return response()->json($result, $result['ok'] ? 200 : 500);
    }
    /**
     * Verificar el estado del servicio IA.
     */
    public function status()
    {
        $service = new GeminiAIService();

        return response()->json([
            'ok'         => true,
            'configured' => $service->isConfigured(),
            'model'      => config('services.gemini.model', 'gemini-2.0-flash'),
        ]);
    }
}









