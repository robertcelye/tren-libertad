{{-- Vista: cuentas de personal (HU-04) --}}
@extends('layouts.app')

@section('titulo', 'Cuentas de personal')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 titulo-modulo mb-0">Cuentas de personal</h1>
        <a href="{{ route('usuarios.create') }}" class="btn btn-tren">Nueva cuenta</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Estado</th><th class="text-end">Acción</th></tr>
                </thead>
                <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre_completo }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->rol?->nombre }}</td>
                        <td>
                            <span class="badge {{ $usuario->activo ? 'text-bg-success' : 'text-bg-secondary' }}">
                                {{ $usuario->activo ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('usuarios.estado', $usuario) }}" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary" @disabled($usuario->is(auth()->user()))>
                                    {{ $usuario->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No hay cuentas de personal.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
@endsection
