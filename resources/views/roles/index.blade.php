{{-- Vista: roles y permisos (HU-05) --}}
@extends('layouts.app')

@section('titulo', 'Roles y permisos')

@section('contenido')
    <h1 class="h3 titulo-modulo mb-3">Roles y permisos</h1>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Rol</th><th>Usuarios</th><th>Permisos</th><th class="text-end">Acción</th></tr>
                </thead>
                <tbody>
                @foreach ($roles as $rol)
                    <tr>
                        <td>
                            <strong>{{ $rol->nombre }}</strong>
                            <div class="small text-muted">{{ $rol->descripcion }}</div>
                        </td>
                        <td>{{ $rol->usuarios_count }}</td>
                        <td>{{ count($rol->listarPermisos()) }}</td>
                        <td class="text-end">
                            <a href="{{ route('roles.edit', $rol) }}" class="btn btn-sm btn-outline-secondary">Editar permisos</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
