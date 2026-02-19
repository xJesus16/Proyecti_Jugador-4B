<?php
namespace App\Servicio;
use App\Models\Partida;
use App\Models\PartidaxJugador;
use App\Servicio\ServicioTurno;
use App\Models\Personaje; 

class ServicioPartida{

    //Crea una partida
    // Entrada
    // Nombre
    // Codigo
    // Idjugador
    function crear ($nombre,$codigo,$idjugador,$idpersonaje){
        
        $resultado = new \stdClass();

        // 1.-Crear la partida en la bd
        $partida = new Partida();
        $partida->nombre = $nombre;
        $partida->codigo = $codigo; 
        // $partida->idpersonaje = $idpersonaje;
        $partida->turno_actual = 0;
        $partida->ganador = 0;
        $partida->fecha = hoy();
        $partida->status = 0;
        $partida->save();

        // 2.-Inscribir al usuario en la partida
        $partidaxjugador = new PartidaxJugador();
        // Para inscribir al usuario que CREO la partida le coloco el id de la partida
        // Que acabo de crear
        $partidaxjugador->idpartida = $partida->id;
        $partidaxjugador->idjugador = $idjugador;
        $partidaxjugador->puntos = 100;
        $partidaxjugador->turno = 0;
        $partidaxjugador->idpersonaje = $idpersonaje;
        $partidaxjugador->status = 0; 
        $partidaxjugador->save();

        $resultado->idpartida=$partida->id;
        $resultado->status='OK';
        return  $resultado;
    }

    function unir_partida($codigo,$idjugador,$idpersonaje){
    $resultado = new \stdClass();

    $partida = Partida::where('codigo',$codigo)->first();
    if($partida){

        $pxj = PartidaxJugador::where('idjugador',$idjugador)
                ->where('idpartida',$partida->id)
                ->first();

        if(!$pxj){

            $partidaxjugador = new PartidaxJugador();
            $partidaxjugador->idpartida = $partida->id; 
            $partidaxjugador->idjugador = $idjugador;
            $partidaxjugador->idpersonaje = $idpersonaje;

            // Regla 1 grupo 2

            if($partida->status == 1){
                $partidaxjugador->turno =
                    PartidaxJugador::where('idpartida',$partida->id)->max('turno') + 1;

                $partidaxjugador->puntos =
                    PartidaxJugador::where('idpartida',$partida->id)->min('puntos');

                $partidaxjugador->status = 1;
            }else{
                $partidaxjugador->turno = 0;
                $partidaxjugador->puntos = 100;
                $partidaxjugador->status = 0;
            }

            $partidaxjugador->save();

            $resultado->status='OK';
            $resultado->idpartida=$partida->id;
            $resultado->idpersonaje=$idpersonaje;
            $resultado->mensaje='Inscripcion exitosa';

        }else{
            $resultado->status='Not OK';
            $resultado->mensaje='El jugador ya esta inscrito';
        }

    }else{
        $resultado->status='Not OK';
        $resultado->mensaje='No existe una partida con el codigo proporcionado';
    }

    return $resultado;
}


    function iniciar_partida($idpartida){
        // 1.- Cambiar el status de la partida a 1
                $partida=Partida::find($idpartida);
                 $partida->status=1;
                 $partida->turno_actual=1;
                $partida->save();
                
        // 2.-El turno actual  de la partida en 1
        PartidaxJugador::where('idpartida', $idpartida)
        ->update(['status'=>1]);
        // 3.-Asignar turnos 
        // 3.1 Obtener  los jugadores de la partida
        $jugadores=PartidaxJugador::where('idpartida',$idpartida)->get();
        // 3.2 COntar los jugadores de la partida
        $numero_jugadores=count($jugadores);
        // 3.3 Crear un arreglo de turno dependiendo del numero de jugadores
        $turnos=range(1,$numero_jugadores);
        // 3.4 Revolver ese arreglo de turnos
        shuffle($turnos);
        // 3.5 Asignar los turnos a los jugadores
        $indice_turnos=0;
        foreach($jugadores as $jugador){
            PartidaxJugador::where('idpartida',$idpartida)
                    ->where('idjugador',$jugador->idjugador)
                    ->update(
                        ["turno"=>$turnos[$indice_turnos]]
                    );
                
                $indice_turnos++;
                
        }
      
        // SELECT * FROM partidaxjugador where idpartida=10;

    }

    function iniciar_turno($idpartida,$idjugador){
        $resultado = new \stdClass();
        // 1.-Obtener el turno actual de la partida
        $partida=Partida::find($idpartida);
        // 2.-Obtener el turno del jugador en la partida
        $partidaxjugador=PartidaxJugador::where('idjugador',$idjugador)
                    ->where ('idpartida',$idpartida)
                    ->first();
        // dd( $partidaxjugador);
        // 3.- Comparo el turno actual con el turno del jugador de la partida
        if($partida->turno_actual==$partidaxjugador->turno){
            //  Si es turno
            $servicio_turno=new ServicioTurno();
            $resultado->status='OK';
            // $resultado=$servicio_turno->iniciar_turno($partida->id,$idjugador);

                
        }
        else{
            // No es turno
            $resultado->status='Not OK';
            $resultado->mensaje='No es tu turno'; 
        }
        return $resultado;
        // dd($r2);
    }
    
    function iniciar_turno_nivel($idpartida, $idjugador, $idnivel){

         $servicio_turno=new ServicioTurno();
         $resultado=$servicio_turno->iniciar_turno($idpartida,$idjugador,$idnivel);
        //  $resultado=$servicio_turno->iniciar_turno($partida->id,$idjugador,$idnivel);
         return $resultado;

    }

    function cambiar_turno($idpartida){
        // Obtener la informacion de la partida
        // $partida=Partida::find($idpartida);
        // $partida->turno_actual++;
        // $partida->save();
        $partida = Partida::find($idpartida);

            // Contar cuántos jugadores tiene la partida
            $total_jugadores = PartidaxJugador::where('idpartida', $idpartida)->count();

            // Incrementar turno
            $partida->turno_actual++;

            // Si el turno supera al número de jugadores, reiniciar a 1
            if($partida->turno_actual > $total_jugadores){
                $partida->turno_actual = 1;
            }

            $partida->save();
    
    }
    

   function detalle_partida($idpartida, $idjugador){
    $resultado = new \stdClass();

    // Obtener la partida directamente
    $partida = Partida::find($idpartida);

    // Obtener todos los jugadores inscritos en la partida con su personaje y puntos
    $jugadores = PartidaxJugador::where('idpartida', $idpartida)
                                ->join('jugador', 'partidaxjugador.idjugador', '=', 'jugador.id')
                                ->join('personaje', 'partidaxjugador.idpersonaje', '=', 'personaje.id')
                                ->select(
                                    'jugador.nombre as nombre_jugador',
                                    'personaje.nombre as nombre_personaje',
                                    'partidaxjugador.puntos'
                                )
                                ->get();

    $resultado->partida = $partida;
    $resultado->jugadores = $jugadores;

    return $resultado;
}


function fin_partida($idpartida, $idjugador){
    $resultado = new \stdClass();

    //1.Recuperar la partida
    $partida = Partida::find($idpartida);

    // Si ya terminó, no permitir modificar
    if ($partida->status == 2) {
        $resultado->status = "Not OK";
        $resultado->mensaje = "La partida ya terminó. Ganador: Jugador ".$partida->ganador;
        return $resultado;
    }

    //2.Buscar el jugador en la partida
    $pxj = PartidaxJugador::where('idpartida', $idpartida)
                           ->where('idjugador', $idjugador)
                           ->first();

    if (!$pxj) {
        $resultado->status = "Not OK";
        $resultado->mensaje = "El jugador no está en esta partida";
        return $resultado;
    }

    //3.Obtener el personaje
    $personaje = Personaje::find($pxj->idpersonaje);

    //4.Verificar si ya alcanzó el objetivo
    if ($pxj->puntos >= $personaje->objetivo) {

        //5.Marcar la partida como terminada
        $partida->status = 2;       
        $partida->ganador = $idjugador;
        $partida->save();

        $resultado->status = "OK";
        $resultado->mensaje = "La partida ha terminado. Ganador: Jugador $idjugador";
        return $resultado;
    }

    // Si no ha alcanzado el objetivo
    $resultado->status = "Not OK";
    $resultado->mensaje = "Aún no alcanzas el objetivo del personaje";
    return $resultado;

}








}
?>
