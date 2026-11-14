<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Http\Requests\StoreMarcaRequest;
use App\Http\Requests\UpdateMarcaRequest;
use App\Http\Requests\StoreModeloRequest;
use App\Http\Requests\UpdateModeloRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    /* ============ VISTA PRINCIPAL (selector de qué editar) ============ */
    public function index(Request $request)
    {
        // Eager loading para evitar N+1
        $categorias = Categoria::withCount('modelos')->orderBy('nombre')->get();
        $marcas     = Marca::withCount('modelos')->orderBy('nombre')->get();
        $modelos    = Modelo::with(['marca', 'categoria'])->latest()->paginate(10);

        return view('catalogo.index', compact('categorias', 'marcas', 'modelos'));
    }

    /* ============================ CATEGORÍAS ============================ */
    public function createCategoria()
    {
        return view('catalogo.categorias.create');
    }

    public function storeCategoria(StoreCategoriaRequest $request)
    {
        Categoria::create($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'categorias'])
            ->with('success', 'Categoría creada correctamente.');
    }

    public function editCategoria(Categoria $categoria)
    {
        return view('catalogo.categorias.edit', compact('categoria'));
    }

    public function updateCategoria(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'categorias'])
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroyCategoria(Categoria $categoria)
    {
        if ($categoria->modelos()->exists()) {
            return back()->with('error', 'No se puede eliminar: hay modelos asociados.');
        }
        $categoria->delete();
        return redirect()->route('catalogo.index', ['tab' => 'categorias'])
            ->with('success', 'Categoría eliminada correctamente.');
    }

    /* ============================== MARCAS ============================== */
    public function createMarca()
    {
        return view('catalogo.marcas.create');
    }

    public function storeMarca(StoreMarcaRequest $request)
    {
        Marca::create($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'marcas'])
            ->with('success', 'Marca creada correctamente.');
    }

    public function editMarca(Marca $marca)
    {
        return view('catalogo.marcas.edit', compact('marca'));
    }

    public function updateMarca(UpdateMarcaRequest $request, Marca $marca)
    {
        $marca->update($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'marcas'])
            ->with('success', 'Marca actualizada correctamente.');
    }

    public function destroyMarca(Marca $marca)
    {
        if ($marca->modelos()->exists()) {
            return back()->with('error', 'No se puede eliminar: hay modelos asociados.');
        }
        $marca->delete();
        return redirect()->route('catalogo.index', ['tab' => 'marcas'])
            ->with('success', 'Marca eliminada correctamente.');
    }

    /* ============================== MODELOS ============================== */
    public function createModelo()
    {
        $marcas     = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        return view('catalogo.modelos.create', compact('marcas', 'categorias'));
    }

    public function storeModelo(StoreModeloRequest $request)
    {
        Modelo::create($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'modelos'])
            ->with('success', 'Modelo creado correctamente.');
    }

    public function editModelo(Modelo $modelo)
    {
        $marcas     = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        return view('catalogo.modelos.edit', compact('modelo', 'marcas', 'categorias'));
    }

    public function updateModelo(UpdateModeloRequest $request, Modelo $modelo)
    {
        $modelo->update($request->validated());
        return redirect()->route('catalogo.index', ['tab' => 'modelos'])
            ->with('success', 'Modelo actualizado correctamente.');
    }

    public function destroyModelo(Modelo $modelo)
    {
        if ($modelo->equipos()->exists()) {
            return back()->with('error', 'No se puede eliminar: hay equipos asociados.');
        }
        $modelo->delete();
        return redirect()->route('catalogo.index', ['tab' => 'modelos'])
            ->with('success', 'Modelo eliminado correctamente.');
    }
}