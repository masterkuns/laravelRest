<?php

namespace App\Http\Controllers;

use App\helpers\JwtAuth;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
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
                'nombres' => 'required',
                'apellidos' => 'required',
                'documentos' => 'required|unique:usuarios',
                'correo' => 'required|email|unique:usuarios',
                'contrasena' => 'required',
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
                $pwd = hash('sha256', $params->contrasena);

                //Crear Usario
                $user = new User();
                $user->nombres = $paramsArray['nombres'];
                $user->apellidos = $paramsArray['apellidos'];
                $user->documentos = $paramsArray['documentos'];
                $user->correo = $paramsArray['correo'];
                $user->contrasena = $pwd;
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

    public function getAllMonitores()
    {

        $usuarios = User::where('rol', 'monitor')->get();

        $respuesta = response()->json(['code' => 200, 'status' => 'success', 'usuarios' => $usuarios]);

        return $respuesta;

    }

    public function getAllCoordinadores()
    {

        $usuarios = User::where('rol', 'monitor')->get();

        $respuesta = response()->json(['code' => 200, 'status' => 'success', 'usuarios' => $usuarios]);

        return $respuesta;

    }
    public function getAllAdministradores()
    {

        $usuarios = User::where('rol', 'administradores')->get();

        $respuesta = response()->json(['code' => 200, 'status' => 'success', 'usuarios' => $usuarios]);

        return $respuesta;

    }
    public function getAllUsers()
    {

        $usuarios = User::whereNull('rol')->get();

        $respuesta = response()->json(['code' => 200, 'status' => 'success', 'usuarios' => $usuarios]);

        return $respuesta;

    }
    public function login(Request $request)
    {
        $jwtAuth = new \App\helpers\JwtAuth();

        //Recoger los Datos POST
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto
        $paramsArray = json_decode($json, true); //array
        //Validar Datos

        $validate = Validator::make($paramsArray, [

            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        if ($validate->fails()) {
            //Validación ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha identificado correctamente',
                'errors' => $validate->errors(),
            );
        } else {
            //Validación correcta
            //Cifrar la contrasena
            $pwd = hash('sha256', $params->contrasena);
            //Devolver datos
            $signup = $jwtAuth->singUp($params->correo, $pwd);
            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->singUp($params->correo, $pwd, true);
            }
            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'El usuario se ha identificado',
            );

            // $correo = "angeldav99@hotmail.com";
            //$contrasena = 'lordaeron';
            // $pwd = hash('sha256', $contrasena);

        }
        return response()->json($signup, 200);
    }
    public function update(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        if ($checkToken && !empty($params_array)) {
            //Actualizar el usuario
            //Conseguir usuario identificado
            $user = $jwtAuth->checkToken($token, true);
            //Validar los datos
            $validate = Validator::make($params_array, [
                'nombres' => 'required|alpha',
                'email' => 'required|email|unique:usuarios,' . $user->sub, //Comprobar si usuario existe (duplicado)
            ]);
            //Quitar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['rol']);
            unset($params_array['contrasena']);
            unset($params_array['apellidos']);
            unset($params_array['estado']);
            //Actualizar el usuario en la base de datos
            $user_update = User::where('id', $user->sub)->update($params_array);
            //Devolver un array con el resultado
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
                'changes' => $params_array,
            );
            echo "<h1>Login Correcto</h1>";
        } else {
            //
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => "Usuario no identificado",
            );
        }
        return response()->json($data, $data['code']);

    }

    public function upload(Request $request)
    {
        //Recoger los datos de la petición
        $image = $request->file('file0');

        //Validar si es una imagen
        $validate = Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);

        //Guardar la imagen
        if (!$image || $validate->fails()) {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => "error al subir imagen",
            );
        } else {
            $image_name = time() . $image->getClientOriginalName();
            Storage::disk('users')->put($image_name, File($image));
            //Devolver el resultado

            $data = array(
                'image' => $image_name,
                'status' => 'success',
                'code' => 200,
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getImage($filename)
    {
        $isset = Storage::disk('users')->exists($filename);

        if ($isset) {
            $file = Storage::disk('users')->get($filename);
            return new HttpResponse($file, 200);
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => "La imagen no existe",
            );
            return response()->json($data, $data['code']);
        }
    }
    public function detail($id)
    {
        $user = User::find($id);
        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user,
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'el usuario no existe',
            );
        }
        return response()->json($data, $data['code']);
    }
}