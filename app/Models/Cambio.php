<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cambio extends Model
{
    use HasFactory;

    protected $fillable = [
        'proceso_id',
        'fecha_inicio',
        'estado',
        'fecha_final',
        'observaciones',
        'editor_id'
    ];

    public function proceso()
    {
        return $this->belongsTo(Proceso::class);
    }

    public function editor()
    {
        return $this->belongsTo(Editor::class, 'editor_id'); // apunta a la columna editor_id
    }
}
