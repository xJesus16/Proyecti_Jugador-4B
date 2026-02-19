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

<form action="{{ route('usuario.save') }}" method="POST">
    @csrf

     <input type="hidden" class="form-control" name="id" value="{{$usuarios->id}}">

     <div class="form-group">
    <label for="exampleInputEmail">Email</label>
    <input type="email" class="form-control" name="email" value="{{$usuarios->email}}">
    </div>

    <div class="form-group">
    <label for="idrol">Rol</label>
    <select name="idrol" class="form-control">
        @foreach($idrol as $rol)
            <option value="{{ $rol->id }}" {{ $usuarios->idrol == $rol->id ? 'selected' : '' }}>
                {{ $rol->nombre }}
            </option>   
        @endforeach
    </select>
    </div>

    <div class="form-group">
    <label for="password">Contraseña</label>
    <input type="password" class="form-control" name="password">
    </div>

    <div>
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
