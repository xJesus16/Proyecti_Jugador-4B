@extends('app.master')

@section('contenido')

<div class="col-md-12">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Hola Nombre del jugador</h3>

      <div class="card-tools">
        <a class="btn btn-info" href="{{ route('partida.formulario') }}">Crear Partida</a>
        <a class="btn btn-warning" href="{{ route('unir') }}">Unir Partida</a>
        <a class="btn btn-danger" href="{{ action([App\Http\Controllers\LoginController::class, 'logout']) }}">Cerrar Sesión</a>
        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <table class="table">
        <tr>
          <td>Nombre</td>
          <td>Puntos</td>
          <td>Personaje</td> 
          <td>Turno actual</td>
          <td>Jugador turno</td> 
          <td>Status</td>
          <td>Detalle</td>
        </tr>

        @foreach($partidas as $partida)
        <tr>
          <td>{{ $partida->nombre }}</td>
          <td>{{ $partida->puntos }}</td>
          <td>{{ $partida->personaje }}</td>
          <td>{{ $partida->turno_actual }}</td>

          <td>
            @if(!empty($partida->jugador_turno))
              
              @if(!empty($partida->jugador_foto))
                <img width="80" src="{{ asset('storage/fotos/' . $partida->jugador_foto) }}" class="rounded-circle" class="img-responsive" alt="foto">
              @endif

              {{ $partida->jugador_turno }}
            @endif
          </td>

          <td>
            @if($partida->status == 0)
              <form action="{{ route('iniciar') }}" method="POST">
                @csrf
                <input type="hidden" name="idpartida" value="{{ $partida->id }}">
                <button class="btn btn-info btn-sm" type="submit">Iniciar Partida</button>
              </form>

            @elseif($partida->status == 1)
              <form action="{{ route('iniciar_turno') }}" method="POST">
                @csrf
                <input type="hidden" name="idpartida" value="{{ $partida->id }}">
                <button class="btn btn-success btn-sm" type="submit">Jugar</button>
              </form>
            @else
              <button type="button" class="btn btn-dark btn-sm">La partida finalizó</button>

            @endif
          </td>
          <td>
              <a href="{{ route('partida.detalle', ['id' => $partida->id]) }}" 
                class="btn btn-primary btn-sm mt-1">
                Detalle
              </a>
          </td>
        </tr>
        @endforeach

      </table>
    </div>

    <div class="card-footer">
      Footer
    </div>
  </div>

</div>

@endsection
