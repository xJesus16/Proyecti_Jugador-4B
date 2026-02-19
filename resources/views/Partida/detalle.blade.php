@extends('app.master')

@section('titulo')
Detalle de la partida
@endsection

@section('contenido')
<div class="col-md-12">

<div class="card-header">
    @if($detalle->partida)
        <h3 class="card-title">Partida: {{ $detalle->partida->nombre }}</h3>
    
    @endif
</div>


    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Jugador</td>
                    <td>Personaje</td>
                    <td>Puntos</td>
                </tr>
            </thead>
            <tbody>
                @foreach($detalle->jugadores as $jugador)
                <tr>
                    <td>{{ $jugador->nombre_jugador }}</td>
                    <td>{{ $jugador->nombre_personaje }}</td>
                    <td>{{ $jugador->puntos }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection 