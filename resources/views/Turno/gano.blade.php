@extends('app.master')

@section('titulo')
Felicidades haz derrotado al enemigo 
@endsection

@section('contenido')
<div class="col-md-12">

    Has ganado {{ $premio }} y vale {{ $puntos }}

    @if (!empty($foto))
        <img width="120" src="{{ asset('storage/foto/tesoro/'.$foto) }}" alt="foto">
    @endif

</div>
@endsection
