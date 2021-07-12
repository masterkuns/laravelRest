<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asistentes extends Model
{
    protected $table = "asistentes";
    protected $fillable = ['idUsuario', 'idEvento',

    ];
    use HasFactory;
    const CREATED_AT = 'fechaInscripcion';
    const UPDATED_AT = 'fechaActualizacion';

}