@extends('layouts.guest2')

@section('title', 'Login')

@section('form')
<form method="POST" action="{{ route('register') }}">
@csrf
    <div class="row" style="margin-top: 80px;">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="card">
                <div class="card-body" style="padding: 5%;">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="name">
                        <label for="floatingInput">Nombre</label>
                        @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="email">
                        <label for="floatingInput">Email</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" name="password" required autocomplete="new-password">
                        <label for="floatingPassword">Contraseña</label>
                        @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="floatingPassword" placeholder="Password" name="password_confirmation" required autocomplete="new-password">
                        <label for="floatingPassword">Confirmar Contraseña</label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="text-center" style="margin-top: 15px;">
                        <a href="{{route('login')}}">
                            <p>¿Ya tienes una cuenta?</p>
                        </a>
                        <button type="submit" class="btn btn-dark w-25">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection