<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;
    protected $table = 'actividades';
    protected $fillable = ['id,horaInicio',
        'horaFinalizacion',
        'monitor'];
    const CREATED_AT = 'fechaCreacion';
    const UPDATED_AT = 'fechaActualizacion';
}