{{-- Vista: crear o editar una ruta (HU-06, UC-02.1). El mismo formulario sirve para ambos casos. --}}
@extends('layouts.app')

@section('titulo', $ruta->exists ? 'Editar ruta' : 'Nueva ruta')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-4">{{ $ruta->exists ? 'Editar ruta' : 'Nueva ruta' }}</h1>

                <form method="POST" action="{{ $ruta->exists ? route('rutas.update', $ruta) : route('rutas.store') }}" novalidate>
                    @csrf
                    @if ($ruta->exists) @method('PUT') @endif

                    <div class="row g-3">
                        <div class="col-sm-4">
                            <label for="codigo" class="form-label">Código</label>
                            <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $ruta->codigo) }}" class="form-control @error('codigo') is-invalid @enderror">
                            @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-8">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $ruta->nombre) }}" class="form-control @error('nombre') is-invalid @enderror">
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="estacion_origen" class="form-label">Estación de origen</label>
                            <input type="text" id="estacion_origen" name="estacion_origen" value="{{ old('estacion_origen', $ruta->estacion_origen) }}" class="form-control @error('estacion_origen') is-invalid @enderror">
                            @error('estacion_origen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="estacion_destino" class="form-label">Estación de destino</label>
                            <input type="text" id="estacion_destino" name="estacion_destino" value="{{ old('estacion_destino', $ruta->estacion_destino) }}" class="form-control @error('estacion_destino') is-invalid @enderror">
                            @error('estacion_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="distancia_km" class="form-label">Distancia (km)</label>
                            <input type="number" step="0.01" min="0" id="distancia_km" name="distancia_km" value="{{ old('distancia_km', $ruta->distancia_km) }}" class="form-control @error('distancia_km') is-invalid @enderror">
                            @error('distancia_km') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="duracion_min" class="form-label">Duración (min)</label>
                            <input type="number" min="1" id="duracion_min" name="duracion_min" value="{{ old('duracion_min', $ruta->duracion_min) }}" class="form-control @error('duracion_min') is-invalid @enderror">
                            @error('duracion_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-4">
                            <label for="estado" class="form-label">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="activa" @selected(old('estado', $ruta->estado) === 'activa')>Activa</option>
                                <option value="suspendida" @selected(old('estado', $ruta->estado) === 'suspendida')>Suspendida</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-tren">Guardar</button>
                        <a href="{{ route('rutas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
