{{-- Vista: registro del turista (HU-03, UC-01.5) --}}
@extends('layouts.app')

@section('titulo', 'Crear cuenta')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-1">Crea tu cuenta</h1>
                <p class="text-muted small mb-4">Con tu cuenta podrás reservar, pagar y consultar tus boletos.</p>

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="name" class="form-label">Nombres</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="given-name"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" autocomplete="family-name"
                                   class="form-control @error('apellidos') is-invalid @enderror">
                            @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-7">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-5">
                            <label for="telefono" class="form-label">Teléfono <span class="text-muted">(opcional)</span></label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" autocomplete="tel"
                                   class="form-control @error('telefono') is-invalid @enderror">
                            @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Mínimo 8 caracteres, con letras y números.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label">Repite la contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                   class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input @error('acepta_datos') is-invalid @enderror" type="checkbox"
                                       id="acepta_datos" name="acepta_datos" value="1" @checked(old('acepta_datos'))>
                                <label class="form-check-label small" for="acepta_datos">
                                    Acepto el tratamiento de mis datos personales conforme a la Ley Orgánica de Protección de Datos Personales.
                                </label>
                                @error('acepta_datos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-tren w-100 mt-4">Crear cuenta</button>
                </form>

                <p class="text-center mt-3 mb-0 small">
                    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
