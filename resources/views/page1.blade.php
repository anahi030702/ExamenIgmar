@extends('layouts.auth')

@section('contenido')
<form action="{{ route('evaluaciones.guardares') }}" method="GET">
  @csrf
  @foreach ($preg as $pre)
  <div class="mb-3">
    <label for="res_usu{{ $pre->id }}" class="form-label">¿Cuál es el resultado de {{ $pre->num1 }} {{ $pre->ope }} {{ $pre->res }} ?</label>
    <input type="text" class="form-control" id="res_usu{{ $pre->id }}" name="res_usu[{{ $pre->id }}]" value="{{ old('res_usu.' . $pre->id, $responses[$pre->id] ?? '') }}">
  </div>
  @endforeach

  <div class="row">
    <div class="col-11">
      <div class="d-flex justify-content-center">
          {{ $preg->links('pagination') }}
      </div>
    </div>
    <div class="col-1" style="margin-top: 5px;">
      @if($preg->currentPage() == $preg->lastPage())
        <button type="submit" class="btn btn-dark">Terminar</button>
      @endif
    </div>
  </div>
</form>
@endsection

