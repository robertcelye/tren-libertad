{{-- Vista: tarifas de una ruta (HU-08) --}}
@extends('layouts.app')

@section('titulo', 'Tarifas '.$ruta->nombre)

@section('contenido')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h3 titulo-modulo mb-0">Tarifas · {{ $ruta->nombre }}</h1>
            <a href="{{ route('rutas.index') }}" class="small">← Volver a rutas</a>
        </div>
        <a href="{{ route('tarifas.create', $ruta) }}" class="btn btn-tren">Nueva tarifa</a>
    </div>

    <div class="row g-3 mb-4">
        @foreach ($vigentes as $tipo => $tarifa)
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Vigente hoy · {{ ucfirst($tipo) }}</div>
                        @if ($tarifa)
                            <div class="display-6">USD {{ $tarifa->calcularPrecio() }}</div>
                            <div class="small text-muted">Valor {{ $tarifa->valor }} + cargo {{ $tarifa->cargo_servicio }}</div>
                        @else
                            <div class="text-danger">Sin tarifa vigente</div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white"><strong>Historial de tarifas</strong></div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Tipo</th><th>Valor</th><th>Cargo</th><th>Desde</th><th>Hasta</th><th>Estado</th><th class="text-end">Acción</th></tr>
                </thead>
                <tbody>
                @forelse ($tarifas as $tarifa)
                    <tr>
                        <td>{{ ucfirst($tarifa->tipo_pasajero) }}</td>
                        <td>{{ $tarifa->valor }}</td>
                        <td>{{ $tarifa->cargo_servicio }}</td>
                        <td>{{ $tarifa->fecha_inicio->format('d/m/Y') }}</td>
                        <td>{{ $tarifa->fecha_fin?->format('d/m/Y') ?? 'Sin fin' }}</td>
                        <td>
                            @if ($tarifa->verificarVigencia())
                                <span class="badge text-bg-success">Vigente</span>
                            @elseif ($tarifa->activa)
                                <span class="badge text-bg-light border">Programada / vencida</span>
                            @else
                                <span class="badge text-bg-secondary">Desactivada</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($tarifa->activa)
                                <form method="POST" action="{{ route('tarifas.desactivar', $tarifa) }}" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-secondary">Desactivar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay tarifas registradas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
