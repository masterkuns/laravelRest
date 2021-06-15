<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([

            'nombres' => $row[0],
            'apellidos' => $row[2],
            'documentos' => $row[3],
            'correo' => $row[4],
            'contrasena' => hash('sha256', $row[5]),

            'rol' => $row[6],

        ]);
    }
}