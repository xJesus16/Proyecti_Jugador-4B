 @extends('app.master')

@section('titulo')
Tipo Jugador
@endsection


 @section('contenido')
 <div class="col-md-12">
        <a class="btn btn-info" href="{{ action([App\Http\Controllers\TipoJugadorController::class, 'formulario']) }}">Agregar</a>

        <table class="table">
                <tr>
                    <td>Id</td>
                    <td>Nombre</td>
                </tr>
                @foreach($lista as $elemento)
                <tr>
                    <td>{{ $elemento->id }}</td>
                    <td>
                        
                        <a href="{{ route('tipo_jugador.formulario', ['id' => $elemento->id]) }}">
                            {{ $elemento->nombre }}
                        </a>
                    </td>
                
                </tr>
                @endforeach

        </table>
    </div>
 </div>
 @endsection