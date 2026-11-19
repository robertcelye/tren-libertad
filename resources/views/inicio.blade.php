{{-- Página de inicio (capa Vista). Hereda el layout y solo define su contenido. --}}
@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <section class="portada p-4 p-md-5 mb-4">
        <h1 class="display-6 fw-semibold">Viaja en el Tren de la Libertad</h1>
        <p class="lead mb-4">
            Reserva y compra tus boletos para la ruta Ibarra – Salinas sin intermediarios,
            y descubre la oferta turística y cultural de cada destino.
        </p>
        @if (Route::has('viajes.index'))
            <a href="{{ route('viajes.index') }}" class="btn btn-light btn-lg">Ver viajes disponibles</a>
        @else
            <span class="badge text-bg-warning">Venta en línea disponible próximamente</span>
        @endif
    </section>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 titulo-modulo">Compra directa</h2>
                    <p class="card-text">Elige tu viaje, reserva tu cupo y paga en línea de forma segura.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 titulo-modulo">Boleto digital</h2>
                    <p class="card-text">Recibe tu boleto con código QR y preséntalo al abordar.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 titulo-modulo">Experiencia turística</h2>
                    <p class="card-text">Conoce atractivos, gastronomía y emprendimientos de Ibarra y Salinas.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
