{{--
    Menú principal (capa Vista).
    Route::has() evita errores mientras las rutas de cada módulo todavía no existen:
    cada opción aparece sola cuando se crea su ruta en los siguientes sprints.
--}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-tren">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="{{ route('inicio') }}">
            Tren de la Libertad
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" href="{{ route('inicio') }}">Inicio</a>
                </li>
                @if (Route::has('viajes.index'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('viajes.index') }}">Viajes</a></li>
                @endif
                @if (Route::has('turismo.index'))
                    <li class="nav-item"><a class="nav-link" href="{{ route('turismo.index') }}">Turismo</a></li>
                @endif
            </ul>

            <ul class="navbar-nav">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a></li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item"><a class="btn btn-light btn-sm ms-lg-2" href="{{ route('register') }}">Registrarse</a></li>
                    @endif
                @endguest

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
