{{-- Vista: asignar permisos a un rol (HU-05, UC-01.3) --}}
@extends('layouts.app')

@section('titulo', 'Permisos de '.$rol->nombre)

@section('contenido')
    <h1 class="h3 titulo-modulo">Permisos del rol {{ $rol->nombre }}</h1>
    <p class="text-muted">{{ $rol->descripcion }}</p>

    <form method="POST" action="{{ route('roles.update', $rol) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            @foreach ($catalogo as $grupo => $permisos)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h2 class="h6">{{ $grupo }}</h2>
                            @foreach ($permisos as $clave => $descripcion)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permisos[]" value="{{ $clave }}"
                                           id="p-{{ $loop->parent->index }}-{{ $loop->index }}"
                                           @checked(in_array($clave, old('permisos', $rol->listarPermisos()), true))>
                                    <label class="form-check-label" for="p-{{ $loop->parent->index }}-{{ $loop->index }}">
                                        {{ $descripcion }} <span class="text-muted small">({{ $clave }})</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-tren">Guardar permisos</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
