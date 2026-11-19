{{-- Vista: crear cuenta de personal (HU-04, UC-01.6) --}}
@extends('layouts.app')

@section('titulo', 'Nueva cuenta de personal')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-4">Nueva cuenta de personal</h1>

                <form method="POST" action="{{ route('usuarios.store') }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="name" class="form-label">Nombres</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" class="form-control @error('apellidos') is-invalid @enderror">
                            @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-7">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-5">
                            <label for="telefono" class="form-label">Teléfono <span class="text-muted">(opcional)</span></label>
                            <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}" class="form-control @error('telefono') is-invalid @enderror">
                        </div>
                        <div class="col-12">
                            <label for="rol_id" class="form-label">Rol</label>
                            <select id="rol_id" name="rol_id" class="form-select @error('rol_id') is-invalid @enderror">
                                <option value="">Selecciona un rol…</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}" @selected(old('rol_id') == $rol->id)>{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                            @error('rol_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="password" class="form-label">Contraseña temporal</label>
                            <input type="password" id="password" name="password" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label">Repite la contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-tren">Crear cuenta</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
