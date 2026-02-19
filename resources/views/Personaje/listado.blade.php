 @extends('app.master')

@section('titulo')
Personaje
@endsection


 @section('contenido')
 <div class="col-md-12">
        <a class="btn btn-info" href="{{ action([App\Http\Controllers\PersonajeController::class, 'formulario']) }}">Agregar</a>

        <table class="table">
                <tr>
                    <td>Id</td>
                    <td>Nombre</td>
                    <td>Foto</td>
                    <td>Objetivo</td>
                </tr>
                @foreach($lista as $elemento)
                <tr>
                    <td>{{ $elemento->id }}</td>
                    <td>
                        
                        <a href="{{ route('personaje.formulario', ['id' => $elemento->id]) }}">
                            {{ $elemento->nombre }}
                        </a>
                    </td>
                    <td>
                                @if ($elemento->foto != '')
                        <img class="img-fluid" width="120" src="{{ asset('storage/foto/personaje/' . $elemento->foto) }}" alt="foto">

                    @endif

                    </td>

                    <td>
                        {{$elemento->objetivo}}
                    </td>
                
                </tr>
                @endforeach

        </table>
    </div>
 </div>
 @endsection