<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramarViajesRequest;
use App\Models\RegistroAuditoria;
use App\Models\Ruta;
use App\Models\Viaje;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

/**
 * Ctrl_RutasViajes · Viajes, horarios, frecuencias y capacidad
 * (HU-06 RF-05.01 UC-02.1, HU-07 RF-05.02 UC-02.2).
 * Permiso requerido: viajes.gestionar.
 */
class ViajesController extends Controller
{
    /** Próximos viajes de una ruta. */
    public function index(Ruta $ruta): View
    {
        $viajes = $ruta->viajes()
            ->whereDate('fecha', '>=', today())
            ->orderBy('fecha')->orderBy('hora_salida')
            ->paginate(20);

        return view('viajes.index', compact('ruta', 'viajes'));
    }

    public function create(Ruta $ruta): View
    {
        return view('viajes.programar', compact('ruta'));
    }

    /**
     * Programa viajes en un rango de fechas, solo los días de la semana elegidos (frecuencia).
     * Omite las fechas que ya tienen un viaje a la misma hora.
     */
    public function store(ProgramarViajesRequest $request, Ruta $ruta): RedirectResponse
    {
        $datos = $request->validated();
        $horaSalida = Carbon::createFromFormat('H:i', $datos['hora_salida'])->format('H:i:s');
        $horaLlegada = isset($datos['hora_llegada']) ? Carbon::createFromFormat('H:i', $datos['hora_llegada'])->format('H:i:s') : null;
        $capacidad = $datos['num_vagones'] * $datos['asientos_por_vagon'];
        $dias = array_map('intval', $datos['dias']);

        $creados = 0;
        $omitidos = 0;

        foreach (CarbonPeriod::create($datos['fecha_inicio'], $datos['fecha_fin']) as $fecha) {
            if (! in_array($fecha->dayOfWeek, $dias, true)) {
                continue;
            }

            $existe = $ruta->viajes()->whereDate('fecha', $fecha)->where('hora_salida', $horaSalida)->exists();
            if ($existe) {
                $omitidos++;
                continue;
            }

            $ruta->viajes()->create([
                'fecha' => $fecha->toDateString(),
                'hora_salida' => $horaSalida,
                'hora_llegada' => $horaLlegada,
                'num_vagones' => $datos['num_vagones'],
                'capacidad' => $capacidad,
                'estado' => 'programado',
            ]);
            $creados++;
        }

        RegistroAuditoria::registrarOperacion('programar_viajes', $ruta, null, [...$datos, 'creados' => $creados]);

        $mensaje = "Se programaron {$creados} viajes.".($omitidos ? " Se omitieron {$omitidos} porque ya existían." : '');

        return redirect()->route('viajes.index', $ruta)->with($creados ? 'exito' : 'error', $mensaje);
    }

    /** Formulario para cambiar vagones y capacidad de un viaje (HU-07). */
    public function editCapacidad(Viaje $viaje): View
    {
        return view('viajes.capacidad', ['viaje' => $viaje->load('ruta')]);
    }

    /** Asigna vagones y recalcula la capacidad sin dejarla por debajo de lo ya reservado o vendido. */
    public function updateCapacidad(Request $request, Viaje $viaje): RedirectResponse
    {
        $ocupados = $viaje->cupos_reservados + $viaje->cupos_vendidos;

        $datos = $request->validate([
            'num_vagones' => ['required', 'integer', 'min:1', 'max:10'],
            'asientos_por_vagon' => ['required', 'integer', 'min:1', 'max:120'],
        ], [
            'num_vagones.required' => 'Indica el número de vagones.',
            'asientos_por_vagon.required' => 'Indica los asientos por vagón.',
        ]);

        $nuevaCapacidad = $datos['num_vagones'] * $datos['asientos_por_vagon'];

        if ($nuevaCapacidad < $ocupados) {
            return back()->withInput()->with('error', "La capacidad no puede ser menor a los {$ocupados} cupos ya reservados o vendidos.");
        }

        $antes = $viaje->only(['num_vagones', 'capacidad']);
        $viaje->asignarCapacidad($datos['num_vagones'], $datos['asientos_por_vagon']);

        RegistroAuditoria::registrarOperacion('asignar_capacidad', $viaje, $antes, $viaje->only(['num_vagones', 'capacidad']));

        return redirect()->route('viajes.index', $viaje->ruta_id)
            ->with('exito', "Capacidad actualizada: {$viaje->capacidad} asientos en {$viaje->num_vagones} vagones.");
    }
}
