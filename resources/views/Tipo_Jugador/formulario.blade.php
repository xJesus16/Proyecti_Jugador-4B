@extends('app.master')

@section('titulo')
Tipo Jugador
@endsection


@section('contenido')

<div class="col-md-12">

    <form action="{{ route('tipo_jugador.save') }}" method="POST">
        @csrf

        <input type="hidden" class="form-control" name="id" value="{{$tipo_jugador->id}}">

        <div class="form-group">
            <label for="exampleInputEmail">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{$tipo_jugador->nombre}}">
        </div>
        <input type="submit" class="btn btn-primary" name="operacion" value="{{$operacion}}">
        @if($operacion=='Modificar')
        <input type="submit" class="btn btn-primary" name="operacion" value="Eliminar">
        @endif
    </form>
</div>
</div>
@endsection