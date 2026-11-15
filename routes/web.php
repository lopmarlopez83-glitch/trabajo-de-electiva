<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // ==================== COMPARTIDO ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

    Route::get('solicitudes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::get('solicitudes/{solicitud}', [SolicitudController::class, 'show'])
        ->whereNumber('solicitud')->name('solicitudes.show');

    // ✅ destroy compartido: admin elimina cualquiera, solicitante solo las suyas pendientes
    Route::delete('solicitudes/{solicitud}', [SolicitudController::class, 'destroy'])
        ->whereNumber('solicitud')->name('solicitudes.destroy');

    Route::get('entregas', [EntregaController::class, 'index'])->name('entregas.index');

    // ==================== SOLO ADMIN ====================
    Route::middleware('role:admin')->group(function () {

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('oficinas', OficinaController::class);

        Route::get('catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

        Route::get('catalogo/categorias/create', [CatalogoController::class, 'createCategoria'])->name('catalogo.categorias.create');
        Route::post('catalogo/categorias', [CatalogoController::class, 'storeCategoria'])->name('catalogo.categorias.store');
        Route::get('catalogo/categorias/{categoria}/edit', [CatalogoController::class, 'editCategoria'])->name('catalogo.categorias.edit');
        Route::put('catalogo/categorias/{categoria}', [CatalogoController::class, 'updateCategoria'])->name('catalogo.categorias.update');
        Route::delete('catalogo/categorias/{categoria}', [CatalogoController::class, 'destroyCategoria'])->name('catalogo.categorias.destroy');

        Route::get('catalogo/marcas/create', [CatalogoController::class, 'createMarca'])->name('catalogo.marcas.create');
        Route::post('catalogo/marcas', [CatalogoController::class, 'storeMarca'])->name('catalogo.marcas.store');
        Route::get('catalogo/marcas/{marca}/edit', [CatalogoController::class, 'editMarca'])->name('catalogo.marcas.edit');
        Route::put('catalogo/marcas/{marca}', [CatalogoController::class, 'updateMarca'])->name('catalogo.marcas.update');
        Route::delete('catalogo/marcas/{marca}', [CatalogoController::class, 'destroyMarca'])->name('catalogo.marcas.destroy');

        Route::get('catalogo/modelos/create', [CatalogoController::class, 'createModelo'])->name('catalogo.modelos.create');
        Route::post('catalogo/modelos', [CatalogoController::class, 'storeModelo'])->name('catalogo.modelos.store');
        Route::get('catalogo/modelos/{modelo}/edit', [CatalogoController::class, 'editModelo'])->name('catalogo.modelos.edit');
        Route::put('catalogo/modelos/{modelo}', [CatalogoController::class, 'updateModelo'])->name('catalogo.modelos.update');
        Route::delete('catalogo/modelos/{modelo}', [CatalogoController::class, 'destroyModelo'])->name('catalogo.modelos.destroy');

        Route::resource('equipos', EquipoController::class);
        Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');

        Route::put('solicitudes/{solicitud}/aprobar', [SolicitudController::class, 'aprobar'])
            ->whereNumber('solicitud')->name('solicitudes.aprobar');
        Route::put('solicitudes/{solicitud}/rechazar', [SolicitudController::class, 'rechazar'])
            ->whereNumber('solicitud')->name('solicitudes.rechazar');

        Route::put('entregas/{entrega}/devolver', [EntregaController::class, 'devolver'])
            ->whereNumber('entrega')->name('entregas.devolver');
    });

    // ==================== SOLO SOLICITANTE ====================
    Route::middleware('role:solicitante')->group(function () {
        Route::get('solicitudes/create', [SolicitudController::class, 'create'])->name('solicitudes.create');
        Route::post('solicitudes', [SolicitudController::class, 'store'])->name('solicitudes.store');

        Route::get('solicitudes/{solicitud}/edit', [SolicitudController::class, 'edit'])
            ->whereNumber('solicitud')->name('solicitudes.edit');
        Route::put('solicitudes/{solicitud}', [SolicitudController::class, 'update'])
            ->whereNumber('solicitud')->name('solicitudes.update');
    });
});

require __DIR__ . '/auth.php';