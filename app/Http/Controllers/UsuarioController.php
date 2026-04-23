<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UsuarioController extends Controller
{
     function lista(): View
    {
         $datos=array();
        $datos = [];
        $datos['lista'] = Usuarios::all();
        return view('usuario.listado')->with($datos);


    }

    function formulario($id = 0): View
    {
        $datos=array();
        $datos = [];
        $datos['idrol'] = Rol::all();

        if ($id == 0) {
            $datos['usuarios'] = new Usuarios();
            $datos['operacion'] = 'Agregar';
        } else {
            $datos['usuarios'] = Usuarios::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('usuario.formulario')->with($datos);
    }

        function save(Request $request)
        {

        $context = $request->all();

        switch($context['operacion']){
            case 'Agregar':
            $usuario = new Usuarios();
            // Izquierda es BD <> Derecha es formulario
            $usuario->email = $context['email'];
            if($context['password']!=''){
            $usuario->password = bcrypt ($context['password']);
            }
            $usuario->idrol = $context['idrol'];
            $usuario->save();
            break;

            case 'Modificar':
            $usuario = Usuarios::find($context['id']);
            $usuario->email = $context['email'];
            if($context['password']!=''){
            $usuario->password = bcrypt ($context['password']);
            }
            $usuario->idrol = $context['idrol'];
            $usuario->save();
            break;

            case 'Eliminar':
            $usuario=Usuarios::find($request->input('id'));
            $usuario->delete();
            break;
            

        }

        return redirect()->route('lista_usuario');

        }




}






