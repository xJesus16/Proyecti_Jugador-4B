<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Mounstro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use View;

class MounstroController extends Controller
{
    function lista()
    {
        $datos = [];
        $datos['lista'] = Mounstro::all();
        return view('mounstro.listado', $datos);
    }

    function formulario($id = 0)
    {
        $datos = [];
        if ($id == 0) {
            $datos['mounstro'] = new Mounstro();
            $datos['operacion'] = 'Agregar';
        } else {
            $datos['mounstro'] = Mounstro::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('mounstro.formulario')->with($datos);
    }

    function guardar(Request $datos)
    {
        $contex = $datos->all();
        $archivo = $datos->file('foto');

        switch ($datos['operacion']) {
            case 'Agregar':
                $mounstro = new Mounstro();
                $mounstro->nombre = $datos['nombre'];
                $mounstro->nivel = $datos['nivel'];
                $mounstro->foto = '';
                $mounstro->save();

                if ($datos->hasFile('foto')) {
                    $archivo = $datos->file('foto');
                    $nombre_archivo = 'foto-' . $mounstro->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('foto/mounstro', $nombre_archivo, 'public');
                    $mounstro->foto = $nombre_archivo;
                    $mounstro->save();
                }
                break;

            case 'Modificar':
                $mounstro = Mounstro::find($datos['id']);
                $mounstro->nombre = $datos['nombre'];
                $mounstro->nivel = $datos['nivel'];
                $mounstro->save();

                if ($datos->hasFile('foto')) {
                    if ($mounstro->foto != '') {
                        Storage::disk('public')->delete('foto/mounstro/' . $mounstro->foto);
                    }
                    $nombre_archivo = 'foto-' . $mounstro->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('foto/mounstro', $nombre_archivo, 'public');
                    $mounstro->foto = $nombre_archivo;
                    $mounstro->save();
                }
                break;

            case 'Eliminar':
                $mounstro = Mounstro::find($datos['id']);
                if ($mounstro->foto != '') {
                    Storage::disk('public')->delete('foto/mounstro/' . $mounstro->foto);
                }
                $mounstro->delete();
                break;
        }

        return redirect()->route('mounstro');
    }

    function mostrar_foto($foto)
    {
        $ruta_archivo = storage_path('app/public/foto/mounstro/' . $foto);
        if (!file_exists($ruta_archivo)) {
            abort(404);
        }
        $file = File::get($ruta_archivo);
        $type = File::mimeType($ruta_archivo);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    function save(Request $datos)
    {
        return $this->guardar($datos);
    }
}
