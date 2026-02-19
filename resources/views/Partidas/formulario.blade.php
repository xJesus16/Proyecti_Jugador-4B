<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="{{ asset('bootstrap.min4b.css') }}">
</head>
<body>
    <div class="container">
        <div class="col-md-12">


<form action="{{ route('partidas.save') }}" method="POST">
    @csrf

     <input type="hidden" class="form-control" name="id" value="{{$partidas->id}}">
   
     <div class="form-group">
    <label for="exampleInputEmail">Nombre</label>
    <input type="text" class="form-control" name="nombre" value="{{$partidas->nombre}}">
    </div>
     <div class="form-group">
    <label for="exampleInputEmail">Fecha de registro</label>
    <input type="date" class="form-control" id="fechaderegistro" name="fechaderegistro" value="2025-09-10">
    </div>
     <div class="form-group">
    <label for="exampleInputEmail">Codigo</label>
    <input type="text" class="form-control" name="codigo" value="{{$partidas->codigo}}">
    </div>
     <input type="submit" class="btn btn-primary" name="operacion" value="{{$operacion}}">
     @if($operacion=='Modificar')
     <input type="submit" class="btn btn-primary" name="operacion" value="Eliminar">
    @endif
</form>
   </div>

     </div>
       <script src="{{ asset('jquery.slim.min4b.js') }}"></script>
        <script src="{{ asset('bootstrap.bundle.min4b.js') }}"></script>
</body>
</html>