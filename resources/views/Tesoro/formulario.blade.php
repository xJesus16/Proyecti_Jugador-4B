@extends('app.master') 

@section('titulo')
Tesoro
@endsection

@section('contenido')

<div class="col-md-12">

    <form action="{{ route('tesoro.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        
        <input type="hidden" class="form-control" name="id" value="{{ $tesoro->id }}">
   
     
        <div class="form-group">
            <label for="nombre">Nombre del Tesoro</label>
            <input type="text" class="form-control" name="nombre" value="{{ $tesoro->nombre }}">
        </div>

         <div class="form-group">
            <label for="foto">Foto</label>
            <input type="file" name="foto" class="form-control">
        </div>
      
        <div class="form-group">
            <label for="nivel">Nivel</label>
            <input type="number" class="form-control" name="nivel" value="{{ $tesoro->nivel }}">
        </div>

 
        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="number" class="form-control" name="valor" value="{{ $tesoro->valor }}">
        </div>

        <input type="submit" class="btn btn-primary" name="operacion" value="{{ $operacion }}">
        @if($operacion == 'Modificar')
            <input type="submit" class="btn btn-danger" name="operacion" value="Eliminar">
        @endif

    </form>

</div>
      
@endsection
