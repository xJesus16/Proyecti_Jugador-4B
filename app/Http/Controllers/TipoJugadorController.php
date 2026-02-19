<?php

namespace App\Http\Controllers;

use App\Models\TipoJugador;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TipoJugadorController extends Controller
{
    
     //Muestra el listado de jugadores.
     
     function lista(): View
    {
        $datos=array();
        $datos = [];
        $datos['lista'] =TipoJugador::all();
        return view('tipo_jugador.listado', $datos);
    }

    
    //   Muestra el formulario para crear un nuevo jugador.
 
   function formulario(int $id = 0): View
    {
        $datos=array();
          $datos = [];
        if($id == 0){
            $datos['tipo_jugador'] =new TipoJugador();
            $datos['operacion']='Agregar';
        }
        else{
               $datos['tipo_jugador'] = TipoJugador::find($id);
                $datos['operacion']='Modificar';
        }
     return view('tipo_jugador.formulario', $datos);

    }

    /**
     * Guarda los datos del jugador enviado desde el formulario.
     */
  function save(Request $request)
{
    // Mostrar todos los datos enviados desde el formulario
    $context = $request->all();
 // Esto detiene la ejecución y muestra los datos
    switch($context['operacion']){
        case 'Agregar':
                    // Crear nuevo jugador con los datos recibidos
            $tipo_jugador = new TipoJugador();
            // Izquierda es BD <> Derecha es formulario
            $tipo_jugador->nombre = $request->input('nombre');
            $tipo_jugador->save();
            // Redirigir a la pagina principal 
            return redirect()->route('tipo_jugador')->with('success', 'Jugador guardado');
          break;
            case 'Modificar':
                $tipo_jugador=TipoJugador::find($request->input('id'));
                 $tipo_jugador->nombre = $request->input('nombre');
                $tipo_jugador->save();
                return redirect()->route('tipo_jugador')->with('success', 'Jugador guardado');
            break;
            case 'Eliminar':
                $tipo_jugador=TipoJugador::find($request->input('id'));
                $tipo_jugador->delete();
                return redirect()->route('tipo_jugador')->with('success', 'Jugador guardado');
            break;
    }

    
}

}