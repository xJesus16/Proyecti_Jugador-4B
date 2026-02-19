@extends('app.master')

@section('titulo')
Felicidades has Iniciado tu turno
@endsection

@section('contenido')
Ya has Iniciado tu turno y Peleas contra {{ $enemigo }}<br/>
y necesitamos para ganarle {{ $danio }}<br/>

<form action="{{ route('atacar_mounstro') }}" method="POST">
    @csrf
    <input type="hidden" name="idturno" value="{{ $idturno }}">
    <button class="btn btn-success">Tirar dado</button>
</form>

@if (!empty($mounstro->foto))
    <img width="120" src="{{ asset('storage/foto/mounstro/' . $mounstro->foto) }}" alt="foto del enemigo">
@endif

@endsection
