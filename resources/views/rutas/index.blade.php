{{-- Vista: rutas (HU-06) --}}
@extends('layouts.app')

@section('titulo', 'Rutas')

@section('contenido')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 titulo-modulo mb-0">Rutas</h1>
        <a href="{{ route('rutas.create') }}" class="btn btn-tren">Nueva ruta</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Código</th><th>Ruta</th><th>Duración</th><th>Viajes próximos</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                </thead>
                <tbody>
                @forelse ($rutas as $ruta)
                    <tr>
                        <td>{{ $ruta->codigo }}</td>
                        <td>{{ $ruta->nombre }}<div class="small text-muted">{{ $ruta->estacion_origen }} → {{ $ruta->estacion_destino }}</div></td>
                        <td>{{ $ruta->duracion_min ? $ruta->duracion_min.' min' : '—' }}</td>
                        <td>{{ $ruta->viajes_proximos_count }}</td>
                        <td><span class="badge {{ $ruta->estado === 'activa' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($ruta->estado) }}</span></td>
                        <td class="text-end text-nowrap">
                            @if (auth()->user()->verificarPermiso('viajes.gestionar'))
                                <a href="{{ route('viajes.index', $ruta) }}" class="btn btn-sm btn-outline-secondary">Viajes</a>
                            @endif
                            @if (auth()->user()->verificarPermiso('tarifas.gestionar'))
                                <a href="{{ route('tarifas.index', $ruta) }}" class="btn btn-sm btn-outline-secondary">Tarifas</a>
                            @endif
                            <a href="{{ route('rutas.edit', $ruta) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay rutas registradas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
