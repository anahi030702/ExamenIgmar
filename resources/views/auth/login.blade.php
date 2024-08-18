@extends('layouts.guest2')

@section('title', 'Login')

@section('form')
<form method="POST" action="{{ route('login') }}">
@csrf
    <div class="row" style="margin-top: 180px;">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="card">
                <div class="card-body" style="padding: 5%;">
                    <div class="text-center">
                        <a href="{{route('register')}}">
                            <p>¿Aun no estas registrado?</p>
                        </a>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="name@example.com" name="email">
                        <label for="floatingInput">Email address</label>
                        @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" name="password">
                        <label for="floatingPassword">Password</label>
                        @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                    <div class="text-center" style="margin-top: 15px;">
                        <a href="{{route('password.request')}}">
                            <p>¿Olvidaste tu contraseña?</p>
                        </a>
                        <button type="submit" class="btn btn-dark w-25">Log In</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection