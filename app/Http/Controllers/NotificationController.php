<?php

namespace App\Http\Controllers;

use App\Models\CustomNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mostrar las últimas 20 notificaciones.
     */
    public function index()
    {
        $notifications = CustomNotification::latest()->take(20)->get();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Eliminar una notificación específica.
     */
    public function destroy($id)
    {
        $notification = CustomNotification::findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notificación eliminada correctamente.');
    }

    /**
     * Marcar todas las notificaciones como leídas.
     */
    public function markAllRead()
    {
        CustomNotification::query()->update(['read' => true]);

        return back()->with('success', 'Todas las notificaciones se marcaron como leídas.');
    }
}
