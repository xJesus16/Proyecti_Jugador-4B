<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Servicio\ServicioPartida;
use App\Servicio\ServicioTurno;
use App\Models\Jugador;
use App\Models\Personaje; 
use App\Models\Mounstro;  
use App\Models\Tesoro;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PartidaController extends Controller{

function formulario(int $id = 0): View
    {
        $datos=array();
          $datos = [];
        if($id == 0){
            $datos['partida'] =new Partida();
            $datos['operacion']='Agregar';
        }
        else{
               $datos['partida'] = Partida::find($id);
                $datos['operacion']='Modificar';
        }
        $datos['personajes'] = Personaje::all();
     return view('partida.formulario', $datos);

    }

    function save(Request $r){
        $context =$r->all();
        // 1.- Obtener el id del usuario de la sesion
        $idusuario=Auth::user()->id;
        // 2.- Obtener el jugador asociado al usuario
        $jugador=Jugador::where('idusuario', $idusuario)->first();

        $servicio=new ServicioPartida();
        // Obtener el usuario de la sesion

        $servicio->crear($context['nombre'], $context['codigo'], $jugador->id, $context['idpersonaje']);
       
        return redirect()->route('profile_jugador');

    }

    function formulario_unir(){
        $datos=array();
        $datos['personajes'] = Personaje::all();
        return view('partida.unir')->with($datos);
    }

    function unir_partida(Request $r){
        $context=$r->all();
        // 1.- Obtener el id del usuario de la sesion
        // $idusuario=Auth::user()->id;
        // 2.- Obtener el jugador asociado al usuario
        // $jugador=Jugador::where('idusuario', $idusuario)->first();
        $jugador=dame_jugador();
        $servicio=new ServicioPartida();
        $r1=$servicio->unir_partida($context['codigo'],$jugador->id,$context['idpersonaje']);
        if($r1->status=='OK'){
            return view('partida.unir_exitoso');
        }

        else{
            $datos=array();
            $datos['mensaje']=$r1->mensaje;
            return view('partida.unir_noexitoso')->with($datos);
        }



    }

    function iniciar(Request $r){
        $context=$r->all();
        $servicio=new ServicioPartida();
        $servicio->iniciar_partida($context['idpartida']);
        // dd($context);
        return view('partida.iniciar_exitoso');
    }

   function iniciar_turno(Request $r){
        $context=$r->all();
        //$idusuario=Auth::user()->id;
        //1-Obtener el idusuario de la sesion
        //$player=Player::where('idusuario', $idusuario)->first();
       $jugador = dame_jugador();
        $servicio = new ServicioPartida();
        $r2 = $servicio->iniciar_turno($context['idpartida'], $jugador->id);
        if($r2->status=='OK'){
            $servicio_turno= new ServicioTurno();
            //Le informo al usuario que su turno se inicio
            //return view('turno.inicio');
            //validar_turno_activo
            $r4=$servicio_turno->validar_turno_activo($context['idpartida'],$jugador->id);
            if($r4->status=='OK'){
                //Si tiene un turno activo
                $datos=array();
                $datos['enemigo']=$r4->nombre_enemigo;
                $datos['danio']=$r4->danio;
                $datos['idturno']=$r4->idturno;
                $datos['mounstro'] = Mounstro::find($r4->idenemigo);
                return view('Turno.inicio')->with($datos);
            }
            else{
                $datos=array();
            $datos['idpartida']=$context['idpartida'];
            return view('Turno.seleccionar_nivel')->with($datos);
            }
            //$datos=array();
            //$datos['idpartida']=$context['idpartidas'];
            //return view('turno.seleccionar_nivel')->with($datos);
        }
        else{
            //Le informo al usuario el error
            $datos=array();
            $datos['mensaje']=$r2->mensaje;
            return view('Turno.noinicio')->with($datos);
        }
       
    }

    function iniciar_turno_nivel(Request $r){
        $context=$r->all();
        $jugador = dame_jugador();
        $servicio = new ServicioPartida();
        $resultado=$servicio->iniciar_turno_nivel($context['idpartida'],$jugador->id,$context['idnivel']);
        if($resultado->status=='OK'){
            $datos=array();
            $datos['enemigo']=$resultado->nombre_enemigo;
            $datos['danio']=$resultado->danio; 
            $datos['idturno']=$resultado->idturno; 
            $datos['mounstro'] = Mounstro::find($resultado->idenemigo);
            return view('Turno.inicio')->with($datos);
        }
    }

    function atacar_mounstro(Request $r){
        $context=$r->all();
        $servicio_turno=new ServicioTurno();
        $r6=$servicio_turno->atacar($context['idturno']);
        if($r6->status=='OK'){
            $datos=array();
            $datos['premio']=$r6->nombre_premio;
            $datos['puntos']=$r6->puntos;
             $datos['foto']   = $r6->foto;
            return view('turno.gano')->with($datos);
        }
        else{
            $datos=array();
            $datos['idturno']=$context['idturno'];
            return view('turno.perdio')->with($datos);
            return view('turno.perdio');
        }
    }

   function ataque_mounstro(Request $r){
    $context = $r->all();
    $servicio_turno = new ServicioTurno();
    $r6 = $servicio_turno->ataque_mounstro($context['idturno']); 
    $datos = [];
    $datos['mensaje'] = $r6->mensaje;
    return view('turno.resultado_ataque')->with($datos);
    }   



     function detalle($idpartida = null)
{
    $jugador = dame_jugador();
    $servicio = new ServicioPartida();

    // Llamas al servicio con el id de la partida
    $detalle = $servicio->detalle_partida($idpartida, $jugador->id);

    // Pasas el resultado a la vista
    return view('partida.detalle')->with(['detalle' => $detalle]);
}



}



//  $servicio_turno->atacar($context['idturno']);



?>