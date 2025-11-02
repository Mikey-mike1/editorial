<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
{
    use HasFactory;

    // Forzar la tabla correcta
    protected $table = 'editores';

    protected $fillable = ['nombre'];

    public function procesos()
    {
        return $this->hasMany(Proceso::class);
    }

    public function cambios()
    {
        return $this->hasMany(Cambio::class);
    }
}

