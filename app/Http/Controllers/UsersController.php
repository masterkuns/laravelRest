<?php

namespace App\Http\Controllers;

use App\helpers\JwtAuth;
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
                'documentos' => 'required|unique:usuarios',
                'correo' => 'required|email|unique:usuarios',
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
                $pwd = hash('sha256', $params->contraseña);

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
    public function login(Request $request)
    {

        $jwtAuth = new \App\helpers\JwtAuth();

        $correo = "angeldav99@hotmail.com";
        $contraseña = 'lordaeron';
        $pwd = hash('sha256', $contraseña);

        return response()->json($jwtAuth->singUp($correo, $pwd, true, ), 200);
    }
}