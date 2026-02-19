@extends('app.master') 

@section('titulo')
Personaje
@endsection

@section('contenido')

<div class="col-md-12">

    <form action="{{ route('personaje.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Campo oculto para ID -->
        <input type="hidden" class="form-control" name="id" value="{{ $personaje->id }}">
   
        <!-- Nombre -->
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{ $personaje->nombre }}">
        </div>

        <!-- Foto -->
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <!-- Objetivo -->
        <div class="form-group">
            <label for="objetivo">Objetivo</label>
            <input type="text" class="form-control" name="objetivo" value="{{ $personaje->objetivo }}">
        </div>

        <!-- Botones de acción -->
        <input type="submit" class="btn btn-primary" name="operacion" value="{{ $operacion }}">
        @if($operacion == 'Modificar')
            <input type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
        @endif

    </form>

</div>
      
@endsection
