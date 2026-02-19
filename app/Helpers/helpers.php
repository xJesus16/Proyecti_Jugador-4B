<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Jugador;
use PhpParser\Builder\Function_;


function hola(){
    dd('hola');
}
function tirar_dado(){
    return rand(2,12);
}

function hoy($formato='Y-m-d H:i:s'){
    date_default_timezone_set("America/Merida");
    return date($formato);
}
function dame_usuario(){
    return Auth::user();
}
function dame_jugador(){
    //1 obtener el idusuario de la sesion
    $idusuario = Auth::user()->id;
    // 2 obtener el jugador asociado al jugador
    $jugador=Jugador::where('idusuario', $idusuario)->first();
    return $jugador;
    // return Jugador::where('idusuario', $idusuario)->first();
}
//function valida_permiso_usuario($cvepermiso){
    //$permiso-DB::table('usuario');
//}










?>