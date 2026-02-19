@extends('app.master')

@section('titulo')
Tesoro
@endsection

@section('contenido')
<div class="col-md-12">
    <a class="btn btn-info" href="{{ action([App\Http\Controllers\TesoroController::class, 'formulario']) }}">Agregar</a>

    <table class="table">
        <tr>
            <td>Id</td>
            <td>Nombre</td>
            <td>Foto</td>
            <td>Nivel</td>
            <td>Valor</td>
        </tr>
        @foreach($lista as $elemento)
        <tr>
            <td>{{ $elemento->id }}</td>
            <td>
                <a href="{{ route('tesoro.formulario', ['id' => $elemento->id]) }}">
                    {{ $elemento->nombre }}
                </a>
            </td>
            <td>
                @if ($elemento->foto != '')
                    <img class="img-fluid" width="120" src="{{ asset('storage/foto/tesoro/' . $elemento->foto) }}" alt="foto">
                @endif
            </td>
            <td>{{ $elemento->nivel }}</td>
            <td>{{ $elemento->valor }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
