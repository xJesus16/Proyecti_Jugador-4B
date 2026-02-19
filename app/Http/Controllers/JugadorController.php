<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Servicio\ServicioPartida;
use App\Servicio\ServicioTurno;
use App\Models\TipoJugador;
use App\Models\Edad;
use App\Models\Jugador;
use App\Models\Personaje;
use App\Models\PartidaxJugador;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JugadorController extends Controller
{
    function lista(): View
    {
        $datos = [];

        $datos['lista'] = Jugador::join('tipo_jugador', 'jugador.tipo', '=', 'tipo_jugador.id')
            ->join('edad', 'jugador.edad', '=', 'edad.id')
            ->select(
                'jugador.nombre',
                'edad.nombre as edad',
                'jugador.puntos',
                'jugador.id',
                'jugador.foto',
                'tipo_jugador.nombre as tipo'
            )
            ->get();

        return view('jugador.listado', $datos);
    }

  function formulario(int $id = 0): View
    {
        $datos = [];
        $datos['tipos'] = TipoJugador::all();
        $datos['edades'] = Edad::all();

        if ($id == 0) {
            $datos['jugador'] = new Jugador();
            $datos['operacion'] = 'Agregar';
        } else {
            $datos['jugador'] = Jugador::find($id);
            $datos['operacion'] = 'Modificar';
        }

        return view('jugador.formulario', $datos);
    }

    function save(Request $request)
    {
        $context = $request->all();
        $archivo = $request->file('foto');

        switch ($context['operacion']) {
            case 'Agregar':
                $jugador = new Jugador();
                $jugador->nombre = $request->input('nombre');
                $jugador->edad = $request->input('edad');
                $jugador->puntos = $request->input('puntos');
                $jugador->tipo = $request->input('tipo');
                $jugador->foto = '';
                $jugador->save();

                if ($request->hasFile('foto')) {
                    $nombre_archivo = 'foto-' . $jugador->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('fotos', $nombre_archivo, 'public');
                    $jugador->foto = $nombre_archivo;
                    $jugador->save();
                }

                return redirect()->route('jugador')->with('success', 'Jugador guardado');
                break;

            case 'Modificar':
                $jugador = Jugador::find($request->input('id'));
                $jugador->nombre = $request->input('nombre');
                $jugador->edad = $request->input('edad');
                $jugador->puntos = $request->input('puntos');
                $jugador->tipo = $request->input('tipo');

                if ($request->hasFile('foto')) {
                    if ($jugador->foto != '') {
                        Storage::disk('public')->delete('fotos/' . $jugador->foto);
                    }

                    $nombre_archivo = 'foto-' . $jugador->id . '.' . $archivo->getClientOriginalExtension();
                    $archivo->storeAs('fotos', $nombre_archivo, 'public');
                    $jugador->foto = $nombre_archivo;
                }

                $jugador->save();
                return redirect()->route('jugador')->with('success', 'Jugador guardado');
                break;

            case 'Eliminar':
                $jugador = Jugador::find($request->input('id'));
                $jugador->delete();

                if ($jugador->foto != '') {
                    Storage::disk('public')->delete('fotos/' . $jugador->foto);
                }

                return redirect()->route('jugador')->with('success', 'Jugador guardado');
                break;
        }
    }
      function mostrar_foto($archivo)
    {
        $path = storage_path('app/public/fotos/' . $archivo);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
    function bienvenido()
    {
        dd('ufff bienvenido jugador');
    }

    function autoregistro_form()
{
    $datos = [];
    $datos['tipos'] = TipoJugador::all();
    $datos['edades'] = Edad::all();

    return view('jugador.autoregistro')->with($datos);
}

function autoregistro(Request $r)
{
    $context = $r->all();

    // 1.- Registrar un usuario
    $usuario = new Usuarios();
    $usuario->email = $context['email'];

    if ($context['password'] != '') {
        $usuario->password = bcrypt($context['password']);
    }

    $usuario->idrol = 1;
    $usuario->save();

    // 2.- Registrar un jugador
    $jugador = new Jugador();
    $jugador->nombre = $context['nombre'];
    $jugador->edad = $context['edad'];
    $jugador->puntos = 0;
    $jugador->tipo = $context['tipo'];
    $jugador->foto = '';
    $jugador->idusuario = $usuario->id;

    if ($r->hasFile('foto')) {
        $archivo = $r->file('foto');
        $nombre_archivo = 'foto-' . $jugador->id . '.' . $archivo->getClientOriginalExtension();
        $archivo_subido = $archivo->storeAs('fotos', $nombre_archivo, 'public');
        $jugador->foto = $nombre_archivo;
        $jugador->save();
    }

    $jugador->save();

    // 3.- Iniciar sesión
    Auth::loginUsingId($usuario->id);

    return redirect()->route('profile_jugador');
}


 function profile()
{
    $idusuario = Auth::user()->id;
    $jugador = Jugador::where('idusuario', $idusuario)->first();

    $partidas = DB::table('partida')
    ->join('partidaxjugador','partida.id','=','partidaxjugador.idpartida')
    ->join('personaje','personaje.id','=','partidaxjugador.idpersonaje')
    ->join('jugador','jugador.id','=','partidaxjugador.idjugador')
    ->select(
        'partida.nombre',
        'partida.id',
        'partida.status',
        'partida.turno_actual',
        'partidaxjugador.puntos',
        'partidaxjugador.turno',
        'personaje.nombre as personaje',
        'partida.ganador'
    )
    ->where('partidaxjugador.idjugador',$jugador->id)
    ->get();

    foreach ($partidas as $partida) {
        $jugador_turno=DB::table('partidaxjugador')
            ->join('jugador','partidaxjugador.idjugador','=','jugador.id')
            ->where('partidaxjugador.idpartida', $partida->id)
            ->where('partidaxjugador.turno', $partida->turno_actual)
            ->select('jugador.nombre','jugador.foto')
            ->first();

        $partida->jugador_turno=$jugador_turno?$jugador_turno->nombre:'Desconocido';
        $partida->jugador_foto=$jugador_turno?$jugador_turno->foto:null;

        if ($partida->status == 2 && $partida->ganador) {
            $ganador = DB::table('jugador')
                ->where('id', $partida->ganador)
                ->value('nombre');
            $partida->nombre_ganador = $ganador;
        }
    }
    $datos['partidas'] = $partidas;

    return view('jugador.profile')->with($datos);
}
/*
function profile()
{
    $idusuario = Auth::user()->id;
    $jugador = Jugador::where('idusuario', $idusuario)->first();

    $partidas = DB::table('partida')
        ->join('partidaxjugador', function ($join) use ($jugador) {
            $join->on('partida.id', '=', 'partidaxjugador.idpartida')
                 ->where('partidaxjugador.idjugador', $jugador->id);
        })
        ->join('personaje', 'personaje.id', '=', 'partidaxjugador.idpersonaje')
        ->leftJoin('partidaxjugador as turno', function($join) use ($jugador){
            $join->on('turno.idpartida', '=', 'partida.id')
                 ->on('turno.turno', '=', 'partida.turno_actual')
                 ->where('turno.idjugador', $jugador->id);
        })
        ->leftJoin('jugador as jugadorTurno', 'jugadorTurno.id', '=', 'turno.idjugador')
        ->select(
            'partida.nombre',
            'partida.id',
            'partida.status',
            'partida.turno_actual',
            'partidaxjugador.puntos',
            'personaje.nombre as personaje',
            'jugadorTurno.nombre as jugador_turno',
            'jugadorTurno.foto as jugador_foto',
            'turno.turno as turno_jugador_actual'
        )
        ->get();

        

    return view('jugador.profile')->with(['partidas' => $partidas]);
}*/



    function formulario_unir(){
        $datos = [];
        $datos['personajes'] = Personaje::all();
        return view('partida.unir')->with($datos);
    }

    function unir_partida(Request $r){
        $context = $r->all();
        $jugador = dame_jugador();
        $servicio = new ServicioPartida();
        $r1 = $servicio->unir_partida($context['codigo'], $jugador->id, $context['idpersonaje']);
        if($r1->status == 'OK'){
            return view('partida.unir_exitoso');
        } else {
            $datos = [];
            $datos['mensaje'] = $r1->mensaje;
            return view('partida.unir_noexitoso')->with($datos);
        }
    }

    function iniciar(Request $r){
        $context = $r->all();
        $servicio = new ServicioPartida();
        $servicio->iniciar_partida($context['idpartida']);
        return view('partida.iniciar_exitoso');
    }

    function iniciar_turno(Request $r){
        $context = $r->all();
        $jugador = dame_jugador();
        $servicio = new ServicioPartida();
        $r2 = $servicio->iniciar_turno($context['idpartida'], $jugador->id);
        if($r2->status == 'OK'){
            $servicio_turno = new ServicioTurno();
            $r4 = $servicio_turno->validar_turno_activo($context['idpartida'], $jugador->id);
            if($r4->status == 'OK'){
                $datos = [];
                $datos['enemigo'] = $r4->nombre_enemigo;
                $datos['danio'] = $r4->danio;
                $datos['idturno'] = $r4->idturno;
                return view('turno.inicio')->with($datos);
            } else {
                $datos = [];
                $datos['idpartida'] = $context['idpartida'];
                return view('turno.seleccionar_nivel')->with($datos);
            }
        } else {
            $datos = [];
            $datos['mensaje'] = $r2->mensaje;
            return view('turno.noinicio')->with($datos);
        }
    }

    function iniciar_turno_nivel(Request $r){
        $context = $r->all();
        $jugador = dame_jugador();
        $servicio = new ServicioPartida();
        $resultado = $servicio->iniciar_turno_nivel($context['idpartida'], $jugador->id, $context['idnivel']);
        if($resultado->status == 'OK'){
            $datos = [];
            $datos['enemigo'] = $resultado->nombre_enemigo;
            $datos['danio'] = $resultado->danio;
            $datos['idturno'] = $resultado->idturno;
            return view('turno.inicio')->with($datos);
        }
    }

    
}
     