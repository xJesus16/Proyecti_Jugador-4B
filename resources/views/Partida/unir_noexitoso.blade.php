    @extends('app.master')

@section('titulo')
Hubo un error al unirse
@endsection

@section('contenido')

<div class="col-md-12">
{{$mensaje}}
</div>

@endsection
