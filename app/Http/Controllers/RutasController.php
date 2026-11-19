<?php

namespace App\Http\Controllers;

use App\Http\Requests\RutaRequest;
use App\Models\RegistroAuditoria;
use App\Models\Ruta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Ctrl_RutasViajes · Rutas (HU-06, RF-05.01, UC-02.1).
 * Permiso requerido: rutas.gestionar (operador ferroviario, administrador del sistema).
 */
class RutasController extends Controller
{
    public function index(): View
    {
        $rutas = Ruta::withCount(['viajes as viajes_proximos_count' => fn ($q) => $q->whereDate('fecha', '>=', today())])
            ->orderBy('nombre')
            ->get();

        return view('rutas.index', compact('rutas'));
    }

    public function create(): View
    {
        return view('rutas.formulario', ['ruta' => new Ruta(['estado' => 'activa'])]);
    }

    public function store(RutaRequest $request): RedirectResponse
    {
        $ruta = Ruta::create($request->validated());

        RegistroAuditoria::registrarOperacion('crear_ruta', $ruta, null, $ruta->only(['codigo', 'nombre', 'estado']));

        return redirect()->route('rutas.index')->with('exito', "Se creó la ruta {$ruta->nombre}.");
    }

    public function edit(Ruta $ruta): View
    {
        return view('rutas.formulario', compact('ruta'));
    }

    public function update(RutaRequest $request, Ruta $ruta): RedirectResponse
    {
        $antes = $ruta->only(array_keys($request->validated()));
        $ruta->update($request->validated());

        RegistroAuditoria::registrarOperacion('modificar_ruta', $ruta, $antes, $ruta->only(array_keys($antes)));

        return redirect()->route('rutas.index')->with('exito', "Se actualizó la ruta {$ruta->nombre}.");
    }
}
