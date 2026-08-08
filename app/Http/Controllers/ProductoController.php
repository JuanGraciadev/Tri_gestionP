<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Display a listing of products for Admin management.
     */
    public function index(): View
    {
        $productos = Producto::with('categoria')
            ->orderBy('id_producto', 'desc')
            ->get();

        $categorias = Categoria::all();

        $totalProductos = $productos->count();
        $activos = $productos->where('estado', true)->count();
        $inactivos = $totalProductos - $activos;
        $totalCategorias = $categorias->count();

        return view('productos.index', compact(
            'productos',
            'categorias',
            'totalProductos',
            'activos',
            'inactivos',
            'totalCategorias'
        ));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductoRequest $request): RedirectResponse
    {
        $imagenPath = $this->procesarImagen($request);

        if ($imagenPath === false) {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error de imagen',
                'text' => 'La imagen no es válida. Usa JPG, PNG, WEBP o GIF (máx 5MB).',
            ]);
            return redirect()->route('productos.index');
        }

        $user = auth()->user();
        $idUsuario = $user->id_usuario ?? $user->id;

        $created = Producto::create([
            'nombre' => $request->validated('nombre'),
            'precio' => $request->validated('precio'),
            'img' => $imagenPath ?? '',
            'id_usuario' => $idUsuario,
            'id_categoria' => $request->validated('id_categoria'),
            'retornable' => $request->boolean('retornable'),
            'estado' => true,
        ]);

        if ($created) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Producto creado correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo crear el producto.',
            ]);
        }

        return redirect()->route('productos.index');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        $imagenPath = $this->procesarImagen($request);

        if ($imagenPath === false) {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error de imagen',
                'text' => 'La imagen no es válida. Usa JPG, PNG, WEBP o GIF (máx 5MB).',
            ]);
            return redirect()->route('productos.index');
        }

        $updated = $producto->update([
            'nombre' => $request->validated('nombre'),
            'precio' => $request->validated('precio'),
            'img' => $imagenPath ?? ($request->input('img_actual') ?? ''),
            'id_categoria' => $request->validated('id_categoria'),
            'retornable' => $request->boolean('retornable'),
        ]);

        if ($updated) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Actualizado!',
                'text' => 'Producto actualizado correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar el producto.',
            ]);
        }

        return redirect()->route('productos.index');
    }

    /**
     * Toggle the active status of a product.
     */
    public function toggleEstado(Producto $producto): RedirectResponse
    {
        $nuevoEstado = !$producto->estado;
        $producto->estado = $nuevoEstado;
        $saved = $producto->save();

        if ($saved) {
            $nuevoEstadoStr = $nuevoEstado ? 'habilitado' : 'inhabilitado';
            session()->flash('alert', [
                'icon' => 'success',
                'title' => 'Estado Actualizado',
                'text' => "El producto ha sido {$nuevoEstadoStr}.",
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo cambiar el estado del producto.',
            ]);
        }

        return redirect()->route('productos.index');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Producto $producto): RedirectResponse
    {
        if ($producto->img && file_exists(public_path($producto->img))) {
            @unlink(public_path($producto->img));
        }

        $deleted = $producto->delete();

        if ($deleted) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'Producto eliminado correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el producto.',
            ]);
        }

        return redirect()->route('productos.index');
    }

    /**
     * Display client public/client product catalog.
     */
    public function catalogo(Request $request): View
    {
        $filtroCat = $request->query('cat');

        $query = Producto::with('categoria')->where('estado', true);

        if ($filtroCat) {
            $query->where('id_categoria', $filtroCat);
        }

        $productos = $query->orderBy('id_producto', 'desc')->get();
        $categorias = Categoria::where('estado', true)->get();

        return view('productos.catalogo', compact('productos', 'categorias', 'filtroCat'));
    }

    /**
     * Helper: Process uploaded product image file.
     */
    private function procesarImagen(Request $request): string|null|bool
    {
        if (!$request->hasFile('img_file')) {
            return null; // No file uploaded
        }

        $file = $request->file('img_file');

        if (!$file->isValid()) {
            return false;
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return false;
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return false;
        }

        $uploadDir = public_path('img/productos');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = $file->getClientOriginalExtension() ?: 'png';
        $newName = 'prod_' . uniqid() . '.' . $ext;

        $file->move($uploadDir, $newName);

        return 'img/productos/' . $newName;
    }
}
