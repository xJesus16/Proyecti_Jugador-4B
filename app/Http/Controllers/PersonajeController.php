<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Personaje;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use View;

class PersonajeController extends Controller
{
    function lista()
    {
        $datos=array();
        $datos['lista']=Personaje::all();
        return view('personaje.listado',$datos);
    }

    function formulario($id=0)
    {
        $datos=array();
        if ($id==0){
            $datos['personaje']= new Personaje();
            $datos['operacion']='Agregar';
        }
        else{
            $datos['personaje']=Personaje::find($id);
            $datos['operacion']='Modificar';
        }


        //recupero la informacion del jugador a partir del id
        //$c=Jugador::find($id);
        return view('personaje.formulario')->with($datos);
    }

    function guardar(Request $datos)
    {
        //Recoge todos los datos del formulario
        //leo el paquete de texto
        $contex=$datos->all();
        $archivo=$datos->file('foto');
        switch($datos['operacion']){
            case 'Agregar':
                $personaje=new Personaje();
                $personaje->nombre=$datos['nombre'];
                $personaje->objetivo=$datos['objetivo'];
                $personaje->foto='';
                $personaje->save();
                //subirlo al filesystem
                if ($datos->hasFile('foto')){
                    $archivo=$datos->file('foto');
                    $nombre_archivo='foto-'.$personaje->id.'.'.$archivo->getClientOriginalExtension();
                    $archivo_subido=$archivo->storeAs('foto/personaje', $nombre_archivo,'public');
                    $personaje->foto=$nombre_archivo;
                    $personaje->save();
                }
            break;
            case 'Modificar':
                $personaje=Personaje::find($datos['id']);
                $personaje->nombre=$datos['nombre'];
                $personaje->objetivo=$datos['objetivo'];
                $personaje->save();
                if ($datos->hasFile('foto')){
                    //Eliminar la foto anterior
                    if ($personaje->foto!=''){
                        Storage::disk('public')->delete('foto/personaje/' . $personaje->foto);
                    }
                    //Subir la nueva foto
                    $nombre_archivo='foto-'.$personaje->id.'.'.$archivo->getClientOriginalExtension();
                    $archivo_subido=$archivo->storeAs('foto/personaje', $nombre_archivo,'public');
                    $personaje->foto=$nombre_archivo;
                    $personaje->save();
                }
            break;

            case 'Eliminar':
                $personaje=Personaje::find($datos['id']);
                if ($personaje->foto!=''){
                        Storage::disk('public')->delete('foto/personaje/' . $personaje->foto);
                    }
                $personaje->delete();
            break;
        }

        return redirect()->route('personaje');

    }

    function mostrar_foto($foto)
    {
        $ruta_archivo=storage_path('app/public/foto/personaje/'.$foto);
        if (!file_exists($ruta_archivo)){
            abort(404);
        }
        $file= File::get($ruta_archivo);
        $type=File::mimeType($ruta_archivo);
        $response=Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }


    function save(Request $datos)
{
    return $this->guardar($datos);
}



}