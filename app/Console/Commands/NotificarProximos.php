<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Proceso;
use App\Models\Cambio;
use App\Models\CustomNotification;
use App\Models\User;
use Carbon\Carbon;

class NotificarProximos extends Command
{
    protected $signature = 'notificar:proximos';
    protected $description = 'Crea notificaciones para procesos y cambios próximos a vencer para todos los usuarios';

    public function handle()
    {
        $hoy = Carbon::today(); // Solo fecha, sin hora
        $usuarios = User::all();

        if ($usuarios->isEmpty()) {
            $this->info('No hay usuarios registrados. No se crearán notificaciones.');
            return;
        }

        // --------------------------
        // NOTIFICACIONES DE PROCESOS
        // --------------------------
        $procesos = Proceso::whereNotNull('fecha_final')->get();

        foreach ($procesos as $p) {
            $fechaEntrega = Carbon::parse($p->fecha_final)->startOfDay();
            $diasRestantes = $hoy->diffInDays($fechaEntrega, false);

            if (in_array($diasRestantes, [0,1,2,3,4,5])) {
                // Verificar si ya existe la notificación para este proceso hoy
                $existe = CustomNotification::where('proceso_id', $p->id)
                    ->whereDate('created_at', $hoy)
                    ->first();

                if (!$existe) {
                    foreach ($usuarios as $user) {
                        CustomNotification::create([
                            'user_id'    => $user->id,
                            'title'      => "Entrega próxima del proceso",
                            'message'    => "El proceso '{$p->descripcion}' vence en $diasRestantes día(s) ({$fechaEntrega->format('d/m/Y')})",
                            'type'       => 'warning',
                            'proceso_id' => $p->id,
                            'cambio_id'  => null,
                            'read'       => false,
                        ]);
                    }
                }
            }
        }

        // -------------------------
        // NOTIFICACIONES DE CAMBIOS
        // -------------------------
        $cambios = Cambio::whereNotNull('fecha_final')->get();

        foreach ($cambios as $c) {
            $fechaEntrega = Carbon::parse($c->fecha_final)->startOfDay();
            $diasRestantes = $hoy->diffInDays($fechaEntrega, false);

            if (in_array($diasRestantes, [0,1,2,3,4,5])) {
                // Verificar si ya existe la notificación para este cambio hoy
                $existe = CustomNotification::where('cambio_id', $c->id)
                    ->whereDate('created_at', $hoy)
                    ->first();

                if (!$existe) {
                    foreach ($usuarios as $user) {
                        CustomNotification::create([
                            'user_id'    => $user->id,
                            'title'      => "Entrega próxima del cambio #{$c->id}",
                            'message'    => "El cambio del proceso {$c->id} {$c->tipo}  vence en $diasRestantes día(s) ({$fechaEntrega->format('d/m/Y')})",
                            'type'       => 'warning',
                            'proceso_id' => null,
                            'cambio_id'  => $c->id,
                            'read'       => false,
                        ]);
                    }
                }
            }
        }

        $this->info('Notificaciones generadas correctamente para todos los usuarios.');
    }
}
