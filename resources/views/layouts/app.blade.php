{{--
    Layout común (capa Vista).
    Todas las pantallas heredan esta plantilla con @extends('layouts.app')
    y solo escriben su contenido en @section('contenido').
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Inicio') · {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('estilos')
</head>
<body>
    @include('layouts.menu')

    <main class="container py-4">
        {{-- Mensajes que envían los controladores con ->with('exito', '...') o ->with('error', '...') --}}
        @if (session('exito'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('exito') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('contenido')
    </main>

    <footer class="pie py-3 mt-auto">
        <div class="container small d-flex flex-column flex-md-row justify-content-between">
            <span>Tren de la Libertad · Ruta Ibarra – Salinas</span>
            <span>Empresa Pública Administradora</span>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
