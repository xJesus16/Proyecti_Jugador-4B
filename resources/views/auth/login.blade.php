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

    <form action="{{action([App\Http\Controllers\LoginController::class, 'iniciar_sesion'])}}" method="POST">
    <input type="hidden" class="form-control" name="id" value="{{$usuario->id}}">
    @csrf
     <div class="form-group">
    <label for="exampleInputEmail">Email</label>
    <input type="email" class="form-control" name="email" value="">
    </div>
      <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        <label for="password" class="col-md-4 control-label">Password</label>
        <div class="col-md-12">
            <input id="password" type="password" class="form-control" name="password" required>
        </div>
    </div>

    <!-- <div class="form-group">
    <label for="password">Contraseña</label>
    <input type="password" class="form-control" name="password">
    </div> -->

    <div>
     <input type="submit" class="btn btn-primary" name="operacion" value="Iniciar Sesion">
     <a class="btn btn-warning" href="{{ route('sign_up') }}">Crea tu cuenta</a>
     
</form>
   </div>

    </div>
       <script src="{{ asset('jquery.slim.min4b.js') }}"></script>
        <script src="{{ asset('bootstrap.bundle.min4b.js') }}"></script>
</body>
</html> 
