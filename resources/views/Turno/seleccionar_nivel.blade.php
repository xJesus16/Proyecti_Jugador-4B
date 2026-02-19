@extends('app.master')

@section('titulo')
Selecciona un nivel
@endsection

@section('contenido')
<div class="col-md-12">

    <form action="{{ route('iniciar_turno_nivel') }}" method="POST">
    @csrf
    <input type="hidden" name="idpartida" value="{{ $idpartida }}">
    <select class="form-control" name="idnivel" required>
        <option value="1">Nivel 1</option>
        <option value="2">Nivel 2</option>
        <option value="3">Nivel 3</option>
        <option value="4">Nivel 4</option>
        <option value="5">Nivel 5</option>
        <option value="6">Nivel 6</option>
    </select>
    <button class="btn btn-success mt-2" type="submit">Iniciar turno</button>
</form>


</div>
@endsection
