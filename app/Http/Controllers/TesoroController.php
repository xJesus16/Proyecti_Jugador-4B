<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tesoro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class TesoroController extends Controller
{
    // Listado de tesoros
    function lista()
    {
        $datos = array();
        $datos['lista'] = Tesoro::all();
        return view('tesoro.listado', $datos);
    }

    // Formulario de agregar/modificar tesoro
    function formulario($id = 0)
    {
        $datos = array();
        if ($id == 0) {
            $datos['tesoro'] = new Tesoro();
            $datos['operacion'] = 'Agregar';
        } else {
            $datos['tesoro'] = Tesoro::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('tesoro.formulario')->with($datos);
    }

    // Guardar tesoro (Agregar, Modificar, Eliminar)
    function guardar(Request $datos)
    {
        $archivo = $datos->file('foto');

        switch ($datos['operacion']) {
            case 'Agregar':
                $tesoro = new Tesoro();
                $tesoro->nombre = $datos['nombre'];
                $tesoro->nivel = $datos['nivel'];
                $tesoro->valor = $datos['valor'];
                $tesoro->foto = '';
                $tesoro->save();

                if ($datos->hasFile('foto')) {
                    $nombre_archivo = 'tesoro-' . $tesoro->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('foto/tesoro', $nombre_archivo, 'public');
                    $tesoro->foto = $nombre_archivo;
                    $tesoro->save();
                }
            break;

            case 'Modificar':
                $tesoro = Tesoro::find($datos['id']);
                $tesoro->nombre = $datos['nombre'];
                $tesoro->nivel = $datos['nivel'];
                $tesoro->valor = $datos['valor'];
                $tesoro->save();

                if ($datos->hasFile('foto')) {
                    if ($tesoro->foto != '') {
                        Storage::disk('public')->delete('foto/tesoro/' . $tesoro->foto);
                    }
                    $nombre_archivo = 'tesoro-' . $tesoro->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('foto/tesoro', $nombre_archivo, 'public');
                    $tesoro->foto = $nombre_archivo;
                    $tesoro->save();
                }
            break;

            case 'Eliminar':
                $tesoro = Tesoro::find($datos['id']);
                if ($tesoro->foto != '') {
                    Storage::disk('public')->delete('foto/tesoro/' . $tesoro->foto);
                }
                $tesoro->delete();
            break;
        }

        return redirect()->route('tesoro');
    }

    // Mostrar foto del tesoro
    function mostrar_foto($foto)
    {
        $ruta_archivo = storage_path('app/public/foto/tesoro/' . $foto);
        if (!file_exists($ruta_archivo)) {
            abort(404);
        }
        $file = File::get($ruta_archivo);
        $type = File::mimeType($ruta_archivo);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    // Alias para guardar
    function save(Request $datos)
    {
        return $this->guardar($datos);
    }
}
