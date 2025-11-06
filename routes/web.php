<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CambioController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\UserController;

// --------------------------
// RUTAS DE AUTENTICACIÓN
// --------------------------
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');

// --------------------------
// DASHBOARD DEL ADMINISTRADOR
// --------------------------
Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard principal del administrador
    Route::get('/dashboard', [ProcesoController::class, 'adminDashboard'])->name('dashboard');

    // Procesos por cliente
    Route::get('/admin/clientes/{cliente}/procesos', [ProcesoController::class, 'procesosCliente'])
        ->name('clientes.procesos');

// --------------------------
// CRUD DE USUARIOS (ADMIN)
// --------------------------
Route::get('/admin/usuarios', [UserController::class, 'index'])->name('usuarios.index');
Route::get('/admin/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
Route::post('/admin/usuarios', [UserController::class, 'store'])->name('usuarios.store');
Route::get('/admin/usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');
Route::get('/admin/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
Route::put('/admin/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
Route::delete('/admin/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');



    // --------------------------
    // CRUD DE CLIENTES (ADMIN)
    // --------------------------
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/admin/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/admin/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/admin/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/admin/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/admin/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    // --------------------------
    // CRUD DE PROCESOS (ADMIN)
    // --------------------------
    Route::get('/admin/procesos', [ProcesoController::class, 'index'])->name('procesos.index');
    Route::get('/admin/procesos/create', [ProcesoController::class, 'create'])->name('procesos.create');
    Route::post('/admin/procesos', [ProcesoController::class, 'store'])->name('procesos.store');
    Route::get('/admin/procesos/{proceso}', [ProcesoController::class, 'show'])->name('procesos.show');
    Route::get('/admin/procesos/{proceso}/edit', [ProcesoController::class, 'edit'])->name('procesos.edit');
    Route::put('/admin/procesos/{proceso}', [ProcesoController::class, 'update'])->name('procesos.update');
    Route::delete('/admin/procesos/{proceso}', [ProcesoController::class, 'destroy'])->name('procesos.destroy');
    Route::get('/procesos/{id}/flujograma', [ProcesoController::class, 'flujograma'])->name('procesos.flujograma');


    // --------------------------
    // CRUD DE CAMBIOS (ADMIN)
    // --------------------------
    Route::get('/admin/cambios', [CambioController::class, 'index'])->name('cambios.index');
    Route::get('/admin/cambios/create', [CambioController::class, 'create'])->name('cambios.create');
    Route::post('/admin/cambios', [CambioController::class, 'store'])->name('cambios.store');
    Route::get('/admin/cambios/{cambio}', [CambioController::class, 'show'])->name('cambios.show');
    Route::get('/admin/cambios/{cambio}/edit', [CambioController::class, 'edit'])->name('cambios.edit');
    Route::put('/admin/cambios/{cambio}', [CambioController::class, 'update'])->name('cambios.update');
    Route::delete('/admin/cambios/{cambio}', [CambioController::class, 'destroy'])->name('cambios.destroy');

    //--------------------------
    // RUTAS DE NOTIFICACIONES
    //--------------------------
Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])
    ->name('notifications.destroy');

    // --------------------------
// CRUD DE EDITORES (ADMIN)
// --------------------------
Route::get('/admin/editores', [EditorController::class, 'index'])->name('admin.editores.index');
Route::get('/admin/editores/create', [EditorController::class, 'create'])->name('admin.editores.create');
Route::post('/admin/editores', [EditorController::class, 'store'])->name('admin.editores.store');
Route::get('/admin/editores/{editor}/edit', [EditorController::class, 'edit'])->name('admin.editores.edit');
Route::put('/admin/editores/{editor}', [EditorController::class, 'update'])->name('admin.editores.update');
Route::delete('/admin/editores/{editor}', [EditorController::class, 'destroy'])->name('admin.editores.destroy');

// Vista principal del cronómetro
Route::get('/admin/cronometro', [ProcesoController::class, 'cronometro'])
    ->name('admin.cronometro');

// Datos del tbody para AJAX
Route::get('/admin/cronometro/data', [ProcesoController::class, 'cronometroData'])
    ->name('admin.cronometro.data');


});

// --------------------------
// RUTA DE INICIO (REDIRECCIÓN)
// --------------------------
Route::get('/', function () {
    return redirect()->route('dashboard');
});


// Mostrar formulario de consulta
Route::get('/consulta', [ConsultaController::class, 'form'])->name('consulta.form');

// Procesar formulario y mostrar resultados
Route::post('/consulta', [ConsultaController::class, 'resultados'])->name('consulta.resultados');

