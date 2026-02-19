@extends('app.master') 

@section('titulo')
Jugadores
@endsection

@section('contenido')

        <div class="col-md-12">

<a class="btn btn-info" href="{{ action([App\Http\Controllers\LoginController::class, 'logout']) }}">Cerrar Sesión</a>
<a class="btn btn-info" href="{{ action([App\Http\Controllers\JugadorController::class, 'formulario']) }}">Agregar</a>

<table class="table" >
    <thead>
        <tr>
            <td>Id</td>
            <td>Nombre</td>
            <td>Edad</td>
            <td>Puntos</td>
            <td>Tipo</td>
            <td>Foto</td>
        </tr>
    </thead>
    <body>
        @foreach($lista as $elemento)
        <tr>
            <td>{{ $elemento->id }}</td>
            <td>
                <a  href="{{ route('jugador.formulario', ['id' => $elemento->id]) }}">
                    {{ $elemento->nombre }}
                </a>
            </td>
            <td>{{ $elemento->edad }}</td>
            <td>{{ $elemento->puntos}}</td>
            <td>{{ $elemento->tipo}}</td>
           <td>
                     @if ($elemento->foto != '')
                <img class width="120
                " src="{{ asset('storage/fotos/' . $elemento->foto) }}" alt="foto">
            @endif


                  </td>
                  


        </tr>
        @endforeach
    </body> 
</table>

   </div>
      
   @endsection