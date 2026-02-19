    @extends('app.master')

@section('titulo')
Haz fallado en tu ataque
@endsection

@section('contenido')
<div class="col-md-12">

    Preparate para el ataque del mounstro<br/>
    <form action="{{ route('ataque_mounstro') }}" class="form" method="POST">
            @csrf
        <input type="hidden" name="idturno" value="{{$idturno}}">
    <button type="submit" class="btn btn-danger">Ataque del Mounstro!!!</button>

    </form>
    

</div>
@endsection
