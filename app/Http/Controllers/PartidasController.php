<?php

namespace App\Http\Controllers;

use App\Models\Partidas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PartidasController extends Controller
{
    
     //Muestra el listado de jugadores.
     
     function lista(): View
    {
        $datos=array();
        $datos = [];
        $datos['lista'] =Partidas::all();
        return view('partidas.listado', $datos);
    }

    
    //   Muestra el formulario para crear un nuevo jugador.
 
   function formulario(int $id = 0): View
    {
         $datos=array();
          $datos = [];
        if($id == 0){
            $datos['partidas'] =new Partidas();
            $datos['operacion']='Agregar';
        }
        else{
               $datos['partidas'] = Partidas::find($id);
                $datos['operacion']='Modificar';
        }
     return view('partidas.formulario', $datos);

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
            $partidas = new Partidas();
            // Izquierda es BD <> Derecha es formulario
            $partidas->nombre = $request->input('nombre');
            $partidas->fechaderegistro = $request->input('fechaderegistro');
            $partidas->codigo = $request->input('codigo');
            $partidas->save();

          break;
            case 'Modificar':
                $partidas=Partidas::find($request->input('id'));
                $partidas->nombre = $request->input('nombre');
                $partidas->fechaderegistro = $request->input('fechaderegistro');
                 $partidas->codigo = $request->input('codigo');
                $partidas->save();
            break;
            case 'Eliminar':
                $partidas=Partidas::find($request->input('id'));
                $partidas->delete();
            break;
            
    }

                return redirect()->route('partidas')->with('success', 'Jugador guardado');

}

}