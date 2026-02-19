@extends('app.master') 

@section('titulo')
Jugadores
@endsection

@section('contenido')



        <div class="col-md-12">

<form action="{{ route('jugador.save') }}" method="POST" enctype="multipart/form-data">
    @csrf

     <input type="hidden" class="form-control" name="id" value="{{$jugador->id}}">
   
     <div class="form-group">
    <label for="exampleInputEmail">Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{$jugador->nombre}}">
    </div>

    <div class="form-group">
      <label for="exampleInputEmail">Edad:</label>
     <select name="edad">
        @foreach($edades as $edad)
        <option value="{{$edad->id}}">{{$edad->nombre}}</option>
        @endforeach
      </select>  
    </div>
    <!-- <div class="form-group">
    <label for="exampleInputEmail1">Foto</label>
    <input type="file"  name="foto"  class="form-control">
  </div> -->

    <div class="form-group">
      <label for="exampleInputEmail">Puntos:</label>
    <input type="text" class="form-control" value="{{$jugador->puntos}}" name="puntos" >
    </div>

    <div class="form-group">
      <label for="exampleInputEmail">Tipo:</label>
      <select name="tipo">
        @foreach($tipos as $tipo)
        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
        @endforeach
      </select>  
    </div>

    <div class="form-group">
      <label for="exampleInputEmail">Foto:</label> 
      <input type="file" name="foto" class="form-control">
    </div>

     <input type="submit" class="btn btn-primary" name="operacion" value="{{$operacion}}">
     @if($operacion=='Modificar')
     <input type="submit" class="btn btn-primary" name="operacion" value="Eliminar">
     @endif

</form>

   </div>
      
   @endsection