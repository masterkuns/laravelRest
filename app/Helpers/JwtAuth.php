<?php
namespace App\helpers;

use App\Models\User;
use Firebase\JWT\JWT;

class JwtAuth
{
    public function __construct()
    {
        $this->key = "claveprueba1999";
    }

    public function singUp($correo, $contraseña, $getToken = null)
    {

        $usuario = User::where([
            'correo' => $correo,
            'contraseña' => $contraseña,
        ])->first();

        $signup = false;
        if (is_object($usuario)) {
            $signup = true;
        }

        if ($signup) {
            $token = array(
                'sub' => $usuario->ids,
                'correo' => $usuario->correo,
                'nombres' => $usuario->nombres,
                'nombres' => $usuario->apellidos,
                'documentos' => $usuario->documentos,
                'rol' => $usuario->rol,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60), //Una semana
            );
            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto',
            );

        }
        return $data;
    }

}