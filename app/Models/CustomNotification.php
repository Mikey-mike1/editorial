<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomNotification extends Model
{
    use HasFactory;

    protected $table = 'custom_notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'read',
        'proceso_id',
        'cambio_id',
    ];


    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
