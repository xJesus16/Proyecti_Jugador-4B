@extends('app.master')

@section('titulo')
Unete a una partida
@endsection

@section('contenido')

<div class="col-md-12">

    <form action="{{ route('unir_partida') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="exampleInputEmail">Codigo</label>
            <input type="text" class="form-control" name="codigo" value="">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail">Personaje</label>
            <select class="form-control" name="idpersonaje">
                @foreach($personajes as $personaje)
                <option value="{{$personaje->id}}">{{$personaje->nombre}}({{$personaje->objetivo}})</option>
                @endforeach
            </select>
        </div>

        <input type="submit" class="btn btn-primary" name="operacion" value="Unir">
        
    </form>
</div>
</div>
@endsection
