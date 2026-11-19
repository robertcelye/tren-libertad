{{-- Vista: inicio de sesión (HU-03, UC-01.1) --}}
@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-4">Iniciar sesión</h1>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" autocomplete="current-password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="recordarme" name="recordarme">
                        <label class="form-check-label" for="recordarme">Recordarme en este equipo</label>
                    </div>

                    <button type="submit" class="btn btn-tren w-100">Ingresar</button>
                </form>

                <p class="text-center mt-3 mb-0 small">
                    ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
