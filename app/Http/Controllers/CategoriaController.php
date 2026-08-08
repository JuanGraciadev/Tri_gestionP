<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categorias = Categoria::withCount('productos')
            ->orderBy('id_categoria', 'desc')
            ->get();

        $totalCats = $categorias->count();
        $catsActivas = $categorias->where('estado', true)->count();
        $totalProds = $categorias->sum('productos_count');

        return view('categorias.index', compact('categorias', 'totalCats', 'catsActivas', 'totalProds'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreCategoriaRequest $request): RedirectResponse
    {
        $imagenPath = $this->procesarImagen($request);

        if ($imagenPath === false) {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error de imagen',
                'text' => 'La imagen no es válida. Usa JPG, PNG, WEBP o GIF (máx 5MB).',
            ]);
            return redirect()->route('categorias.index');
        }

        $created = Categoria::create([
            'nombre' => $request->validated('nombre'),
            'descripcion' => $request->validated('descripcion'),
            'imagen' => $imagenPath ?? ($request->input('imagen') ?? ''),
            'estado' => true,
        ]);

        if ($created) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Categoría creada correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo crear la categoría.',
            ]);
        }

        return redirect()->route('categorias.index');
    }

    /**
     * Update the specified category in storage.
     */
    public function update(UpdateCategoriaRequest $request, Categoria $categoria): RedirectResponse
    {
        $imagenPath = $this->procesarImagen($request);

        if ($imagenPath === false) {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error de imagen',
                'text' => 'La imagen no es válida. Usa JPG, PNG, WEBP o GIF (máx 5MB).',
            ]);
            return redirect()->route('categorias.index');
        }

        $updated = $categoria->update([
            'nombre' => $request->validated('nombre'),
            'descripcion' => $request->validated('descripcion'),
            'imagen' => $imagenPath ?? ($request->input('img_actual') ?? ''),
        ]);

        if ($updated) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Actualizada!',
                'text' => 'Categoría actualizada correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo actualizar la categoría.',
            ]);
        }

        return redirect()->route('categorias.index');
    }

    /**
     * Toggle status (activate / deactivate) of the category.
     */
    public function toggleEstado(Categoria $categoria): RedirectResponse
    {
        $nuevoEstado = !$categoria->estado;
        $categoria->estado = $nuevoEstado;
        $saved = $categoria->save();

        if ($saved) {
            $nuevoEstadoStr = $nuevoEstado ? 'habilitada' : 'inhabilitada';
            session()->flash('alert', [
                'icon' => 'success',
                'title' => 'Estado Actualizado',
                'text' => "La categoría ha sido {$nuevoEstadoStr}.",
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo cambiar el estado de la categoría.',
            ]);
        }

        return redirect()->route('categorias.index');
    }

    /**
     * Delete a category from storage if no products are associated with it.
     */
    public function destroy(Categoria $categoria): RedirectResponse
    {
        $productosCount = $categoria->productos()->count();

        if ($productosCount > 0) {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'No se puede eliminar',
                'text' => "La categoría tiene {$productosCount} producto(s) asociado(s). Debe reasignarlos o eliminarlos primero.",
            ]);
            return redirect()->route('categorias.index');
        }

        // Delete image file if stored locally
        if ($categoria->imagen && file_exists(public_path($categoria->imagen))) {
            @unlink(public_path($categoria->imagen));
        }

        $deleted = $categoria->delete();

        if ($deleted) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Eliminada!',
                'text' => 'Categoría eliminada correctamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la categoría.',
            ]);
        }

        return redirect()->route('categorias.index');
    }

    /**
     * Add a quick product associated with a category.
     */
    public function agregarProducto(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'precio' => ['required', 'numeric', 'min:0'],
            'id_categoria' => ['required', 'exists:categoria,id_categoria'],
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'precio.required' => 'El precio del producto es obligatorio.',
            'id_categoria.exists' => 'La categoría seleccionada no existe.',
        ]);

        $user = auth()->user();
        $idUsuario = $user->id_usuario ?? $user->id;

        $created = Producto::create([
            'nombre' => $request->input('nombre'),
            'precio' => $request->input('precio'),
            'img' => '',
            'id_usuario' => $idUsuario,
            'id_categoria' => $request->input('id_categoria'),
            'estado' => true,
        ]);

        if ($created) {
            session()->flash('alert', [
                'icon' => 'success',
                'title' => '¡Producto Añadido!',
                'text' => 'El producto se ha agregado a la categoría exitosamente.',
            ]);
        } else {
            session()->flash('alert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo agregar el producto.',
            ]);
        }

        return redirect()->route('categorias.index');
    }

    /**
     * Helper: Process uploaded category image.
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

        $uploadDir = public_path('img/categorias');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = $file->getClientOriginalExtension() ?: 'png';
        $newName = 'cat_' . uniqid() . '.' . $ext;

        $file->move($uploadDir, $newName);

        return 'img/categorias/' . $newName;
    }
}
