<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proceso extends Model
{
    use HasFactory;

protected $fillable = ['cliente_id', 'tipo', 'descripcion', 'estado', 'fecha_inicio', 'fecha_final', 'editor_id'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function cambios()
    {
        return $this->hasMany(Cambio::class);
    }

    public function editor()
{
    return $this->belongsTo(Editor::class);
}

}
