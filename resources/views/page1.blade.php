@extends('layouts.auth')

@section('contenido')
<form action="{{ route('evaluaciones.guardares') }}" method="POST">
  @csrf
  @method('PUT')
  @foreach ($preg as $pre)
<div class="mb-3">
    <label for="res_usu{{ $pre->id }}" class="form-label @error('res_usu.'.$pre->id) is-invalid @enderror">
        ¿Cuál es el resultado de {{ $pre->num1 }} {{ $pre->ope }} {{ $pre->num2 }} ?
    </label>
    <input type="text" class="form-control" id="res_usu{{ $pre->id }}" name="res_usu[{{ $pre->id }}]"
           value="{{ old('res_usu.'.$pre->id, $respuestas[$pre->id] ?? '') }}">
    @error('res_usu.'.$pre->id)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
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
        <button type="submit" class="btn btn-dark" name="page" value="finalizar">Terminar</button>
      @endif
    </div>
  </div>
</form>
@endsection


