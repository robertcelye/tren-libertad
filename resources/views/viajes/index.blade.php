{{-- Vista: próximos viajes de una ruta (HU-06, HU-07) --}}
@extends('layouts.app')

@section('titulo', 'Viajes '.$ruta->nombre)

@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 titulo-modulo mb-0">Viajes · {{ $ruta->nombre }}</h1>
            <a href="{{ route('rutas.index') }}" class="small">← Volver a rutas</a>
        </div>
        <a href="{{ route('viajes.create', $ruta) }}" class="btn btn-tren">Programar viajes</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Fecha</th><th>Salida</th><th>Llegada</th><th>Vagones</th><th>Capacidad</th><th>Disponibles</th><th>Estado</th><th class="text-end">Acción</th></tr>
                </thead>
                <tbody>
                @forelse ($viajes as $viaje)
                    <tr>
                        <td>{{ $viaje->fecha->translatedFormat('D d/m/Y') }}</td>
                        <td>{{ substr($viaje->hora_salida, 0, 5) }}</td>
                        <td>{{ $viaje->hora_llegada ? substr($viaje->hora_llegada, 0, 5) : '—' }}</td>
                        <td>{{ $viaje->num_vagones }}</td>
                        <td>{{ $viaje->capacidad }}</td>
                        <td>{{ $viaje->consultarDisponibilidad() }}</td>
                        <td><span class="badge text-bg-light border">{{ ucfirst($viaje->estado) }}</span></td>
                        <td class="text-end"><a href="{{ route('viajes.capacidad', $viaje) }}" class="btn btn-sm btn-outline-secondary">Vagones</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No hay viajes programados.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $viajes->links('pagination::bootstrap-5') }}</div>
@endsection
