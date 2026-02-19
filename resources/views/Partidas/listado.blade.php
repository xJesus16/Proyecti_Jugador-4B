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


<a class="btn btn-info" href="{{ action([App\Http\Controllers\PartidasController::class, 'formulario']) }}">Agregar</a>

        <table class="table">
                <tr>
                    <td>Id</td>
                    <td>Nombre</td>
                    <td>Fecha de Registro</td>
                    <td>Codigo
                    </td>
                </tr>
                @foreach($lista as $elemento)
                
                    
                    <tr>  
                        <td>{{ $elemento->id }}</td>
                        <td><a href="{{ route('partidas.formulario', ['id' => $elemento->id]) }}">{{$elemento->nombre}}</a></td>
                        <!-- <td>{{ $elemento->nombre }}</td> -->
                        <td>{{ $elemento->fechaderegistro }}</td>
                        <td>{{ $elemento->codigo }}</td>
                        
                    </tr>
                
                
                @endforeach

        </table>
    </div>

       </div>
       <script src="{{ asset('jquery.slim.min4b.js') }}"></script>
        <script src="{{ asset('bootstrap.bundle.min4b.js') }}"></script>
</body>
</html>