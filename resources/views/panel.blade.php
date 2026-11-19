{{-- Vista: panel del usuario autenticado. Muestra su rol y sus permisos (HU-03, HU-05). --}}
@extends('layouts.app')

@section('titulo', 'Mi panel')

@section('contenido')
    <h1 class="h3 titulo-modulo">Hola, {{ $usuario->nombre_completo }}</h1>
    <p class="text-muted">
        Rol: <span class="badge text-bg-secondary">{{ $usuario->rol?->nombre ?? 'Sin rol asignado' }}</span>
    </p>

    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="h6">Lo que puedes hacer en el sistema</h2>
            @forelse ($permisos as $permiso)
                <span class="badge rounded-pill text-bg-light border me-1 mb-1">{{ $permiso }}</span>
            @empty
                <p class="mb-0 text-muted">Tu rol todavía no tiene permisos asignados.</p>
            @endforelse
            <p class="small text-muted mt-3 mb-0">Los módulos se habilitarán en este panel a medida que se completen los sprints.</p>
        </div>
    </div>
@endsection
