@extends('app.master')

@section('titulo')
Tipo Partida
@endsection


@section('contenido')

<div class="col-md-12">

    <form action="{{ route('partida.save') }}" method="POST">
        @csrf

        <input type="hidden" class="form-control" name="id" value="{{$partida->id}}">
        <input type="hidden" name="operacion" value="{{ $operacion }}">

        <div class="form-group">
            <label for="exampleInputEmail">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{$partida->nombre}}">
        </div>

        <div class="form-group">
            <label for="exampleInputEmail">Codigo</label>
            <input type="text" class="form-control" name="codigo" value="{{$partida->codigo}}">
        </div>

        <label for="exampleInputEmail">Personaje</label>
        <select class="form-control" name="idpersonaje">
                @foreach($personajes as $personaje)
                <option value="{{$personaje->id}}">{{$personaje->nombre}}({{$personaje->objetivo}})</option>
                @endforeach
            </select> 

        <input type="submit" class="btn btn-primary" name="operacion" value="{{$operacion}}">
        @if($operacion=='Modificar')
        <input type="submit" class="btn btn-primary" name="operacion" value="Eliminar">
        @endif
        
    </form>
</div>
</div>
@endsection