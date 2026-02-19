<?php
namespace App\Servicio;
use App\Models\Partida;
use App\Models\PartidaxJugador;
use App\Models\Mounstro;
use App\Models\Turno;
use App\Models\Danio;
use App\Models\Tesoro;
use App\Servicio\ServicioPartida;
// use App\Servicio\Tesoro;


class ServicioTurno{


    function dame_mounstro($nivel){
        return Mounstro::where('nivel', $nivel)
                        ->inRandomOrder()
                        ->limit(1)
                        ->first();
    }

    function dame_tesoro($nivel){
        return Tesoro::where('nivel',$nivel)
                        ->inRandomOrder()
                        ->limit(1)
                        ->first();
    }


function iniciar_turno($idpartida,$idjugador,$idnivel){
    $resultado=new \StdClass();
    // Registrar en la bd
    $turno=new Turno();
    $turno->idpartida=$idpartida;
    $turno->idjugador=$idjugador;
    $turno->status=1;

    // Obtener un enemigo de forma aleatoria
    $mounstro=$this->dame_mounstro($idnivel);
    // $mounstro=$this->dame_mounstro(3); //Forzar nivel 3 siempre
    // Obtener un enemigo de forma aleatoria
    // Obtener el personaje que tiene el jugador en la partida
            //     SELECT *
            // FROM partidaxjugador
            // WHERE idpartida = 10
            // AND idjugador = 49;

            $pxj=PartidaxJugador::where('idpartida',$idpartida)
                                ->where('idjugador',$idjugador)
                                ->first();

    // Obtener el personaje que tiene el jugador en la partida

            // Obtener el danio necesario para que el personaje le gane al mounstro
                    // SELECT *
                    //     FROM danio
                    //     WHERE idmonstruo = 1
                    //     AND idpersonaje = 3;

                    $danio = Danio::where('idmounstro',$mounstro->id)
                    ->where('idpersonaje',$pxj->idpersonaje)
                    ->first();
            // Obtener el danio necesario para que el personaje le gane al mounstro


    $turno->idenemigo=$mounstro->id;
    $turno->idpremio=0;
    $turno->gano=0;
    $turno->puntos=0;
    $turno->save();
    $resultado->status='OK';
    $resultado->mensaje='Turno creado con exito';
    $resultado->idturno=$turno->id;
    $resultado->idenemigo=$mounstro->id;
    $resultado->nombre_enemigo=$mounstro->nombre;
    $resultado->danio=$danio->valor;
    return $resultado;
}

function validar_turno_activo($idpartida,$idjugador){
    $resultado=new \StdClass();
        //  SELECT *
        // FROM turno
        // WHERE status = 1
        // AND idpartida = 4
        // AND idjugador = 11;

        $turno=Turno::where('status',1)
        ->where('idpartida',$idpartida)
        ->where('idjugador',$idjugador)
        ->first();

        if($turno){
            // El jugador tiene un turno activo en la partida
            $resultado->status='OK';
            $resultado->idenemigo=$turno->idenemigo;
            $resultado->idturno=$turno->id;
            $monst=Mounstro::find($turno->idenemigo);
            $resultado->nombre_enemigo=$monst->nombre;
            // Obtener el personaje del personaje y el jugador

            $pxj=PartidaxJugador::where('idpartida',$idpartida)
                                ->where('idjugador',$idjugador)
                                ->first();

            //  Obtenemos el daño del mounstro con el personaje

                    $danio = Danio::where('idmounstro',$monst->id)
                    ->where('idpersonaje',$pxj->idpersonaje)
                    ->first();
                    $resultado->danio=$danio->valor;

        }
        else{
            $resultado->status='Not ok';
            $resultado->mensaje='No tiene un turno activo entonces escoge un nivel';
        }

        return $resultado;

}



function atacar($idturno){ //Ataque del jugador
    $resultado=new \StdClass();

    // 1.-Obtener la informacion del turno
    $turno=Turno::find($idturno);

    // 2.-Obtener los dados
    // $dados=tirar_dado();  //Dados aleartorios
    $dados=12;   //Forzar para ganar siempre
    // $dados = 0;  //Siempre pierdo   

   
    // 3.-Obtener el personaje del jugador en la partida
    $pxj=PartidaxJugador::where('idpartida',$turno->idpartida)
                        ->where('idjugador',$turno->idjugador)
                        ->first();

    // 4.-Obtener el valor del danio
    $danio = Danio::where('idmounstro',$turno->idenemigo)
                  ->where('idpersonaje',$pxj->idpersonaje)
                  ->first();

    if($dados >= $danio->valor){

        // 5.-Obtener el premio
        $mounstro = Mounstro::find($turno->idenemigo);

        if($dados == 12){
            $tesoro = $this->dame_tesoro(6);
        } else {
            $tesoro = $this->dame_tesoro($mounstro->nivel);
        }

        
        //  regla 2 -> grupo1      
        if ($mounstro->nivel == 6 && $dados == 12) {

            // 100% para el jugador actual o en turno
            $turno->idpremio = $tesoro->id;
            $turno->puntos = $tesoro->valor;

            PartidaxJugador::where('idjugador', $turno->idjugador)
                ->where('idpartida', $turno->idpartida)
                ->increment('puntos', $tesoro->valor);

            // 50% para los otros jugadores
            $otrosJugadores = PartidaxJugador::where('idpartida', $turno->idpartida)
                ->where('idjugador', '!=', $turno->idjugador)
                ->get();

            foreach ($otrosJugadores as $otro) {
                PartidaxJugador::where('idpartida', $otro->idpartida)
                    ->where('idjugador', $otro->idjugador)
                    ->update([
                        'puntos' => $otro->puntos + ($tesoro->valor * 0.5)
                    ]);
            }
        }
       
        //regla 2 -> grupo1


            //regla 1 ->Extra1
              
              if($mounstro->nivel == 5 && $dados ==12){
                
                $jugadorconmasPuntos = PartidaxJugador::where('idpartida', $turno->idpartida)
                    ->orderByDesc('puntos')
                    ->first();

                if ($jugadorconmasPuntos && $jugadorconmasPuntos->idjugador != $turno->idjugador) {
                    
                    $puntosRobadosdeljugador = $jugadorconmasPuntos->puntos;

                    PartidaxJugador::where('idpartida', $turno->idpartida)
                        ->where('idjugador', $jugadorconmasPuntos->idjugador)
                        ->update([
                            'puntos' => 0
                        ]);

                    
                    PartidaxJugador::where('idpartida', $turno->idpartida)
                        ->where('idjugador', $turno->idjugador)
                        ->increment('puntos', $puntosRobadosdeljugador);
                }
        }

        //               //regla 1 ->Extra1

          
     //Crearas un tesoro especial llamado relámpago el cual será de nivel 2, y el cual tendrá el siguiente efecto, si ataca a un mounstro y pierde entonces el jugador se salvará. Se le informa al jugador de este resultado.               
                    
                        

        // 6.-Actualizo el turno
        $turno->gano = 1;
        $turno->idpremio = $tesoro->id;
        $turno->puntos = $tesoro->valor;

        PartidaxJugador::where('idjugador', $turno->idjugador)
            ->where('idpartida', $turno->idpartida)
            ->update([
                'puntos' => $pxj->puntos + $tesoro->valor
            ]);

        // Verificar si ya ganó la partida
        $servicioPartida = new ServicioPartida();
        $servicioPartida->fin_partida($pxj->idpartida, $pxj->idjugador);

        // Finalizar turno
        $turno->status = 0;
        $turno->save();

        $resultado->status='OK';
        $resultado->nombre_premio = $tesoro->nombre;
        $resultado->foto = $tesoro->foto;
        $resultado->puntos = $tesoro->valor;

        // Cambiar turno de la partida
        $servicio=new ServicioPartida();
        $servicio->cambiar_turno($turno->idpartida);

    } else {
        $resultado->status='Not OK';
    }

    return $resultado;
}

 
   function ataque_mounstro($idturno){  //Ataque del mounstro
    $resultado = new \StdClass();
    // $dados = tirar_dado();   //Poner dados aleartorio
    // $dados = 2;  // Jugador pierde turno y puntos
    $dados = 7;   //Jugador a salvo., pierdo pero me salvo
    // $dados = 11;   // Regla 2.2 grupo2

    switch($dados){
        // Me salve
        case 7:
            // 1.- Obtener la información del turno
            $turno = Turno::find($idturno);
            $turno->status = 0;
            $turno->save();
            $servicio = new ServicioPartida();
            $servicio->cambiar_turno($turno->idpartida);
            $resultado->mensaje = 'Te salvaste el enemigo fallo';
            break;

        // Mataron al jugador
        case 2:
            // 0.- Obtener los datos del turno
            $turno = Turno::find($idturno); 
            $mounstro = Mounstro::find($turno->idenemigo);

                        // regla 2.1 -> grupo2
            if ($mounstro->nivel == 3 ) {

                PartidaxJugador::where('idpartida', $turno->idpartida)
                    ->update([
                        'puntos' => 0,
                        'status' => 0
                    ]);

                Turno::where('idpartida', $turno->idpartida)
                    ->update([
                        'idpremio' => 0,
                        'puntos' => 0
                    ]);

                $turno->status = 0;
                $turno->save();

                $servicio = new ServicioPartida();
                $servicio->cambiar_turno($turno->idpartida);

                $resultado->mensaje = 'Todos los jugadores resultaron heridos';

                
                return $resultado;
            }
                //regla 2 -> grupo2
            

            // 1.- Eliminar los puntos de la partida
            $partidaxjugador = PartidaxJugador::where('idjugador', $turno->idjugador)
                                              ->where('idpartida', $turno->idpartida)
                                              ->first();

            // $partidaxjugador->puntos = 0;

            // // 2.- Cambiar el status de la partida x jugador
            // $partidaxjugador->status = 0;
            // $partidaxjugador->save();

            // 3.- Eliminar los premios
            PartidaxJugador::where('idjugador', $turno->idjugador)
                               ->where('idpartida', $turno->idpartida)
                               ->update([
                                'puntos' => 0,
                                'status' => 0
                                //  'turno'  => 0
                                
               ]);

            Turno::where('idjugador', $turno->idjugador)
                 ->where('idpartida', $turno->idpartida)
                 ->update([
                     "idpremio" => 0,
                     "puntos" => 0,
                 ]);

            // 4.- Terminar el turno
            $turno->status = 0;
            $turno->save();
            $servicio = new ServicioPartida();
            $servicio->cambiar_turno($turno->idpartida);

            // 5.- Cambiar el status
            $resultado->mensaje = 'Te han matado, has perdido el turno y te quedaste sin puntos';
            break;

                      case 11:
                $turno = Turno::find($idturno);
                $monstruo = Mounstro::find($turno->idenemigo);
                 $resultado->mensaje = "Intentas huir pero el mounstro te atrapo, vuelves a luchar contra el.";
                $resultado->status = "NOT OK";

                
            break;




        default:
            // Cualquier otro número de dado
            $resultado->mensaje = "El enemigo te atacó pero no pasó nada ";
        break;

          

    }

    return $resultado;

      /* Crearas un tesoro especial llamado relámpago el cual será de nivel 2,
            y el cual tendrá el siguiente efecto, si ataca a un mounstro y pierde entonces el jugador se salvará.
            Se le informa al jugador de este resultado. */

            
         /*   if($tesoro->nivel ==2 && $tesoro->nombre == "Relampago"){
                $turno->status=0;
                $turno->save();
                $servicio=new ServicioPartida();
                $servicio->cambiar_turno($turno->idpartida);
                $resultado->mensaje="Te salvaste el enemigo fallo por el relampago";

            }*/
    
                

            
}

}

