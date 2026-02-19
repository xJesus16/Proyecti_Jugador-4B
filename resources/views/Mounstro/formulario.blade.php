@extends('app.master') 

@section('titulo')
Mounstro
@endsection

@section('contenido')

<div class="col-md-12">

    <form action="{{ route('mounstro.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

       
        <input type="hidden" class="form-control" name="id" value="{{ $mounstro->id }}">
   
      
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" name="nombre" value="{{ $mounstro->nombre }}">
        </div>

  
        <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>

       
        <div class="form-group">
            <label for="nivel">Nivel</label>
            <input type="text" class="form-control" name="nivel" value="{{ $mounstro->nivel }}">
        </div>

        <input type="submit" class="btn btn-primary" name="operacion" value="{{ $operacion }}">
        @if($operacion == 'Modificar')
            <input type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
        @endif

    </form>

</div>
      
@endsection
