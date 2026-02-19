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
<a class="btn btn-info" href="{{ action([App\Http\Controllers\UsuarioController::class, 'formulario']) }}">Agregar</a>

        <table class="table">
                <tr>
                    <td>Id</td>
                    <td>Email</td>
                    <td>Idrol</td>
                </tr>
                @foreach($lista as $elemento)
                <tr>
                    <td>{{ $elemento->id }}</td>
                    <td>
                        
                        <a href="{{ route('usuario.formulario', ['id' => $elemento->id]) }}">
                            {{ $elemento->email }}
                        </a>
                    </td>
                    <td>{{ $elemento->idrol }}</td>    
                </tr>
                @endforeach

        </table>
    </div>
    </div>
       <script src="{{ asset('jquery.slim.min4b.js') }}"></script>
        <script src="{{ asset('bootstrap.bundle.min4b.js') }}"></script>
</body>
</html>