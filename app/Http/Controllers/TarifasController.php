<?php

namespace App\Http\Controllers;

use App\Http\Requests\TarifaRequest;
use App\Models\RegistroAuditoria;
use App\Models\Ruta;
use App\Models\Tarifa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Ctrl_RutasViajes · Tarifas vigentes (HU-08, RF-02.02, UC-02.3).
 * Permiso requerido: tarifas.gestionar (administrador del sistema).
 * Una tarifa no se edita: se registra una nueva con su fecha de vigencia, y la anterior queda en el historial.
 */
class TarifasController extends Controller
{
    public function index(Ruta $ruta): View
    {
        $tarifas = $ruta->tarifas()->orderByDesc('fecha_inicio')->orderBy('tipo_pasajero')->get();

        $vigentes = [
            'nacional' => $ruta->tarifaVigente('nacional'),
            'extranjero' => $ruta->tarifaVigente('extranjero'),
        ];

        return view('tarifas.index', compact('ruta', 'tarifas', 'vigentes'));
    }

    public function create(Ruta $ruta): View
    {
        return view('tarifas.crear', compact('ruta'));
    }

    public function store(TarifaRequest $request, Ruta $ruta): RedirectResponse
    {
        $anterior = $ruta->tarifaVigente($request->tipo_pasajero);

        $tarifa = $ruta->tarifas()->create([
            ...$request->validated(),
            'cargo_servicio' => $request->input('cargo_servicio', 0),
            'moneda' => 'USD',
            'activa' => true,
        ]);

        // RF-06.01: el cambio de tarifa guarda el valor anterior y el nuevo.
        RegistroAuditoria::registrarOperacion(
            'cambiar_tarifa',
            $tarifa,
            $anterior?->only(['tipo_pasajero', 'valor', 'cargo_servicio', 'fecha_inicio']),
            $tarifa->only(['tipo_pasajero', 'valor', 'cargo_servicio', 'fecha_inicio'])
        );

        return redirect()->route('tarifas.index', $ruta)->with('exito', 'Se registró la nueva tarifa.');
    }

    /** Desactiva una tarifa (deja de considerarse vigente). */
    public function desactivar(Tarifa $tarifa): RedirectResponse
    {
        $tarifa->update(['activa' => false]);

        RegistroAuditoria::registrarOperacion('desactivar_tarifa', $tarifa, ['activa' => true], ['activa' => false]);

        return back()->with('exito', 'La tarifa fue desactivada.');
    }
}
