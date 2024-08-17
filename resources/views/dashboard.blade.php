@extends('layouts.auth')

@section('title', 'dashboard')

@section('contenido')
<div class="row">
    <div class="col-4">
        <div class="card" style="width: 18rem;">
            <img src="{{ asset('imagens/ver.jpeg')}}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Ver tus evaluaciones</h5>
                <a href="{{ route('evaluaciones.get')}}" class="btn btn-primary">Ver evaluaciones</a>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card" style="width: 18rem;">
            <img src="{{ asset('imagens/ver.jpeg')}}" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">Crear evaluacion</h5>
                <a href="{{ route('evaluaciones.page1')}}" class="btn btn-primary">Nueva evaluacion</a>
            </div>
        </div>
    </div>
</div>

@endsection