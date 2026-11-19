{{-- Vista: registrar una tarifa con fecha de vigencia (HU-08, UC-02.3) --}}
@extends('layouts.app')

@section('titulo', 'Nueva tarifa')

@section('contenido')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 titulo-modulo mb-1">Nueva tarifa</h1>
                <p class="text-muted small mb-4">Ruta {{ $ruta->nombre }}. Desde la fecha de inicio, las ventas nuevas usarán esta tarifa.</p>

                <form method="POST" action="{{ route('tarifas.store', $ruta) }}" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="tipo_pasajero" class="form-label">Tipo de pasajero</label>
                            <select id="tipo_pasajero" name="tipo_pasajero" class="form-select @error('tipo_pasajero') is-invalid @enderror">
                                <option value="nacional" @selected(old('tipo_pasajero') === 'nacional')>Nacional</option>
                                <option value="extranjero" @selected(old('tipo_pasajero') === 'extranjero')>Extranjero</option>
                            </select>
                            @error('tipo_pasajero') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="valor" class="form-label">Valor (USD)</label>
                            <input type="number" step="0.01" min="0.01" id="valor" name="valor" value="{{ old('valor') }}" class="form-control @error('valor') is-invalid @enderror">
                            @error('valor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="cargo_servicio" class="form-label">Cargo de servicio (USD)</label>
                            <input type="number" step="0.01" min="0" id="cargo_servicio" name="cargo_servicio" value="{{ old('cargo_servicio', '0.00') }}" class="form-control @error('cargo_servicio') is-invalid @enderror">
                            @error('cargo_servicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="fecha_inicio" class="form-label">Vigente desde</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', today()->toDateString()) }}" class="form-control @error('fecha_inicio') is-invalid @enderror">
                            @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="fecha_fin" class="form-label">Hasta <span class="text-muted">(opcional)</span></label>
                            <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" class="form-control @error('fecha_fin') is-invalid @enderror">
                            @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-tren">Registrar tarifa</button>
                        <a href="{{ route('tarifas.index', $ruta) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
