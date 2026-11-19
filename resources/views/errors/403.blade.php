{{-- Vista que muestra Laravel cuando el componente Seguridad bloquea un acceso (error 403). --}}
@extends('layouts.app')

@section('titulo', 'Acceso denegado')

@section('contenido')
    <div class="text-center py-5">
        <h1 class="display-6 titulo-modulo">Acceso denegado</h1>
        <p class="lead">{{ $exception->getMessage() ?: 'No tienes permiso para realizar esta operación.' }}</p>
        <a href="{{ auth()->check() ? route('panel') : route('inicio') }}" class="btn btn-tren">Volver</a>
    </div>
@endsection
