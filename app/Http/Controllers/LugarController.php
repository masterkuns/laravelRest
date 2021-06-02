<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LugarController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $lugar = Lugar::all();
       
        return response()->json(['code' => 200, 'status' => 'succes', 'lugar' => $lugar]);

    }
    public function show($id)
    {

        $lugar = Lugar::find($id);
        if (is_object($lugar)) {
            $respuesta = ['code' => 200, 'status' => 'succes', 'lugar' => $lugar];

        } else {
            $respuesta = ['code' => 404, 'status' => 'error', 'message' => 'el lugar no existe', 'lugar' => $lugar];

        }
        return response()->json($respuesta, $respuesta['code']);

    }
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = array_map('trim', json_decode($json, true));
        if (!empty($params_array)) {
            $validate = Validator::make($params_array, [
                'nombre' => 'required|alpha',
                'descripcion' => 'required',
                'direccion' => 'required',
                'valor' => 'required|numeric',
                'ocupacion' => 'required',
                'estado' => 'required',
            ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se ha podido crear el lugar',
                    'errors' => $validate->errors(),
                );
            } else {
                $lugar = new Lugar();
                $lugar->nombre = $params_array['nombre'];
                $lugar->descripcion = $params_array['descripcion'];
                $lugar->direccion = $params_array['direccion'];
                $lugar->valor = $params_array['valor'];
                $lugar->ocupacion = $params_array['ocupacion'];
                $lugar->estado = $params_array['estado'];
                $lugar->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se ha creado con exito el objeto',
                    'lugar' => $lugar);
            }
            return response()->json($data, $data['code']);
        }
    }

    public function update(Request $request, $id)
    {
        $json = $request->input('json', null);
        $params_array = array_map('trim', json_decode($json, true));
        if (!empty($params_array)) {
            $validate = Validator::make($params_array, [
                'nombre' => 'required',
                'valor' => 'required',
                'ocupacion' => 'required',
                'estado' => 'required',
            ]);
            unset($params_array['id']);

            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se ha podido Actualizar el objeto',
                    'errors' => $validate->errors(),
                );
            } else {
                $lugar = Lugar::where('id', $id)->update(
                    ['nombre' => $params_array['nombre']],
                    ['valor' => $params_array['valor']],
                    ['ocupacion' => $params_array['ocupacion']],
                    ['estado' => $params_array['estado']]);
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se Actualizo con exito el objeto',
                    'luhar' => $lugar);
            }
            return response()->json($data, $data['code']);
        }
    }

    public function destroy(Request $request, $id)
    {

        $lugar = Lugar::find($id);

        if (is_object($lugar) && !empty($lugar)) {
            $lugar->delete();
            $data = [
                'code' => 200,
                'status' => 'success',
                'lugar' => $lugar,
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La entrada no existe',
            ];
        }

        return response()->json($data, $data['code']);
    }
}