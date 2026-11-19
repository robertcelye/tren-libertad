{{-- Vista: programar viajes con frecuencia semanal (HU-06, UC-02.1) --}}
@extends('layouts.app')

@section('titulo', 'Programar viajes')

@section('contenido')
@php($nombresDias = [1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 0 => 'Domingo'])
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-1">Programar viajes</h1>
                <p class="text-muted small mb-4">Ruta {{ $ruta->nombre }}. Se crea un viaje en cada día marcado dentro del rango de fechas.</p>

                <form method="POST" action="{{ route('viajes.store', $ruta) }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="fecha_inicio" class="form-label">Desde</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', today()->toDateString()) }}" class="form-control @error('fecha_inicio') is-invalid @enderror">
                            @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="fecha_fin" class="form-label">Hasta</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', today()->addDays(30)->toDateString()) }}" class="form-control @error('fecha_fin') is-invalid @enderror">
                            @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <span class="form-label d-block">Días de la semana (frecuencia)</span>
                            @foreach ($nombresDias as $numero => $nombre)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="dias[]" value="{{ $numero }}" id="dia{{ $numero }}"
                                           @checked(in_array((string) $numero, old('dias', ['4', '5', '6', '0']), true))>
                                    <label class="form-check-label" for="dia{{ $numero }}">{{ $nombre }}</label>
                                </div>
                            @endforeach
                            @error('dias') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="hora_salida" class="form-label">Hora de salida</label>
                            <input type="time" id="hora_salida" name="hora_salida" value="{{ old('hora_salida', '08:00') }}" class="form-control @error('hora_salida') is-invalid @enderror">
                            @error('hora_salida') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="hora_llegada" class="form-label">Hora de llegada</label>
                            <input type="time" id="hora_llegada" name="hora_llegada" value="{{ old('hora_llegada', '10:00') }}" class="form-control @error('hora_llegada') is-invalid @enderror">
                            @error('hora_llegada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="num_vagones" class="form-label">Vagones</label>
                            <input type="number" min="1" max="10" id="num_vagones" name="num_vagones" value="{{ old('num_vagones', 2) }}" class="form-control @error('num_vagones') is-invalid @enderror">
                            @error('num_vagones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="asientos_por_vagon" class="form-label">Asientos por vagón</label>
                            <input type="number" min="1" max="120" id="asientos_por_vagon" name="asientos_por_vagon" value="{{ old('asientos_por_vagon', 40) }}" class="form-control @error('asientos_por_vagon') is-invalid @enderror">
                            @error('asientos_por_vagon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-tren">Programar</button>
                        <a href="{{ route('viajes.index', $ruta) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
