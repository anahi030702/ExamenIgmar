@extends('layouts.auth')

@section('title', 'Evaluaciones')

@section('contenido')
<table class="table">
  <thead>
    <tr>
      <th scope="col">Nombre</th>
      <th scope="col">Fecha</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($evaluaciones as $evaluacion)
    <tr>
      <td>{{$evaluacion->name}}</td>
      <td>{{$evaluacion->created_at}}</td>
      <td>
        <a href="{{route('evaluaciones.download', $evaluacion->id)}}">
          <button class="btn btn-dark">Descargar resultados</button>
        </a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection

