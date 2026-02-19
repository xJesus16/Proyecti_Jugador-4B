<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JugadorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TipoJugadorController;
use App\Http\Controllers\PersonajeController;
use App\Http\Controllers\PartidaController;
use App\Http\Controllers\TesoroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MounstroController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Registro de jugador
Route::get('/sign_up', [JugadorController::class, 'autoregistro_form'])->name('sign_up');
Route::post('/sign_up/save', [JugadorController::class, 'autoregistro'])->name('jugador.autoregistro');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {

    //  Catálogo Jugador
    Route::get('/jugador', [JugadorController::class, 'lista'])->name('jugador');
    Route::get('/jugador/formulario/{id?}', [JugadorController::class, 'formulario'])->name('jugador.formulario');
    Route::post('/jugador/save', [JugadorController::class, 'save'])->name('jugador.save');
    Route::get('/jugador/foto/{foto}', [JugadorController::class, 'mostrar_foto'])->name('jugador.foto');
    Route::get('/jugador/profile', [JugadorController::class, 'profile'])->name('profile_jugador');
    Route::get('/home/jugador', [JugadorController::class, 'bienvenido'])->name('bienvenido');

    //  Catálogo Personaje
    Route::get('/personaje', [PersonajeController::class, 'lista'])->name('personaje');
    Route::get('/personaje/formulario/{id?}', [PersonajeController::class, 'formulario'])->name('personaje.formulario');
    Route::post('/personaje/save', [PersonajeController::class, 'save'])->name('personaje.save');
    Route::get('/personaje/foto/{foto}', [PersonajeController::class, 'mostrar_foto'])->name('personaje.foto');

    //  Catálogo Mounstro
    Route::get('/mounstro', [MounstroController::class, 'lista'])->name('mounstro');
    Route::get('/mounstro/formulario/{id?}', [MounstroController::class, 'formulario'])->name('mounstro.formulario');
    Route::post('/mounstro/save', [MounstroController::class, 'save'])->name('mounstro.save');
    Route::get('/mounstro/foto/{foto}', [MounstroController::class, 'mostrar_foto'])->name('mounstro.foto');

    //  Catálogo Tesoro
    Route::get('/tesoro', [TesoroController::class, 'lista'])->name('tesoro');
    Route::get('/tesoro/formulario/{id?}', [TesoroController::class, 'formulario'])->name('tesoro.formulario');
    Route::post('/tesoro/save', [TesoroController::class, 'save'])->name('tesoro.save');
    Route::get('/tesoro/foto/{foto}', [TesoroController::class, 'mostrar_foto'])->name('tesoro.foto');

    // Tipo de Jugador
    Route::get('/tipo_jugador', [TipoJugadorController::class, 'lista'])->name('tipo_jugador');
    Route::get('/tipo_jugador/formulario/{id?}', [TipoJugadorController::class, 'formulario'])->name('tipo_jugador.formulario');
    Route::post('/tipo_jugador/save', [TipoJugadorController::class, 'save'])->name('tipo_jugador.save');

    //  Partida
    Route::get('/partida', [PartidaController::class, 'lista'])->name('partida');
    Route::get('/partida/formulario/{id?}', [PartidaController::class, 'formulario'])->name('partida.formulario');
    Route::post('/partida/save', [PartidaController::class, 'save'])->name('partida.save');
    Route::post('/partida/iniciar', [PartidaController::class, 'iniciar'])->name('iniciar');
    Route::post('/partida/iniciar_turno', [PartidaController::class, 'iniciar_turno'])->name('iniciar_turno');
    Route::post('/partida/iniciar_turno_nivel', [PartidaController::class, 'iniciar_turno_nivel'])->name('iniciar_turno_nivel');
    Route::post('/partida/atacar_mounstro', [PartidaController::class, 'atacar_mounstro'])->name('atacar_mounstro');
    Route::post('/partida/ataque_mounstro', [PartidaController::class, 'ataque_mounstro'])->name('ataque_mounstro');
    Route::get('/partida/detalle/{id?}', action: [PartidaController::class, 'detalle'])->name('partida.detalle');

    //  Unirse a partida
    Route::get('/partida/formulario_unir', [PartidaController::class, 'formulario_unir'])->name('unir');
    Route::post('/partida/unir', [PartidaController::class, 'unir_partida'])->name('unir_partida');

    //  Catálogo Usuario
    Route::get('/usuario', [UsuarioController::class, 'lista'])->name('lista_usuario');
    Route::get('/usuario/formulario/{id?}', [UsuarioController::class, 'formulario'])->name('usuario.formulario');
    Route::post('/usuario/save', [UsuarioController::class, 'save'])->name('usuario.save');
});

//  Login
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login/iniciar', [LoginController::class, 'iniciar_sesion'])->name('iniciar_sesion');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::get('/rol', [UsuarioController::class, 'lista'])->name('lista_usuario');
Route::get('/rol/formulario/{id?}', [UsuarioController::class, 'formulario'])->name('usuario.formulario');
Route::post('/rol/save', [UsuarioController::class, 'save'])->name('rol.save');












?>