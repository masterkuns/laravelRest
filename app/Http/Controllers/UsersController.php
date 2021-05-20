<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function register(Request $request)
    {

        //Recoger los Datos POST
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto
        $paramsArray = json_decode($json, true); //array

        if (!empty($params) && !empty($paramsArray)) {
            //Limpiar Datos
            $paramsArray = array_map('trim', $paramsArray);

            //Validar Datos
            $validate = Validator::make($paramsArray, [
                'nombres' => 'required|alpha',
                'apellidos' => 'required|alpha',
                'documentos' => 'required',
                'correo' => 'required|email|unique:users',
                'contraseña' => 'required',
            ]); //Comprobar si el usuario existe
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creados',
                    'errors' => $validate->errors());

                return response()->json($data, $data['code']);
            } else {
                //Cifrar Pass
                $pwd = password_hash($params->contraseña, PASSWORD_BCRYPT, ['cost' => 4]);

                //Crear Usario
                $user = new User();
                $user->nombres = $paramsArray['nombres'];
                $user->apellidos = $paramsArray['apellidos'];
                $user->documentos = $paramsArray['documentos'];
                $user->correo = $paramsArray['correo'];
                $user->contraseña = $pwd;
                //Guardar el Usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario creado correctamentes');

                return response()->json($data, $data['code']);
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los Datos Enviados no son correctos');

            return response()->json($data, $data['code']);
        }

    }
}