{{-- Vista: asignar vagones y capacidad a un viaje (HU-07, UC-02.2) --}}
@extends('layouts.app')

@section('titulo', 'Vagones y capacidad')

@section('contenido')
@php($ocupados = $viaje->cupos_reservados + $viaje->cupos_vendidos)
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-1">Vagones y capacidad</h1>
                <p class="text-muted small">
                    {{ $viaje->ruta->nombre }} · {{ $viaje->fecha->format('d/m/Y') }} · {{ substr($viaje->hora_salida, 0, 5) }}<br>
                    Capacidad actual: {{ $viaje->capacidad }} · Ocupados (reservados + vendidos): {{ $ocupados }}
                </p>

                <form method="POST" action="{{ route('viajes.capacidad.update', $viaje) }}" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="num_vagones" class="form-label">Vagones</label>
                            <input type="number" min="1" max="10" id="num_vagones" name="num_vagones" value="{{ old('num_vagones', $viaje->num_vagones) }}" class="form-control @error('num_vagones') is-invalid @enderror">
                            @error('num_vagones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label for="asientos_por_vagon" class="form-label">Asientos por vagón</label>
                            <input type="number" min="1" max="120" id="asientos_por_vagon" name="asientos_por_vagon"
                                   value="{{ old('asientos_por_vagon', $viaje->num_vagones ? intdiv($viaje->capacidad, $viaje->num_vagones) : 40) }}"
                                   class="form-control @error('asientos_por_vagon') is-invalid @enderror">
                            @error('asientos_por_vagon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-tren">Guardar</button>
                        <a href="{{ route('viajes.index', $viaje->ruta_id) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
