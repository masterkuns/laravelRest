<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $eventos = Eventos::all();
        $respuesta = response()->json(['code' => 200, 'status' => 'succes', 'eventos' => $eventos]);

        return $respuesta;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $eventos = Eventos::find($id);
        if (is_object($eventos)) {
            $respuesta = ['code' => 200, 'status' => 'succes', 'eventos' => $eventos];

        } else {
            $respuesta = ['code' => 404, 'status' => 'error', 'message' => 'el eventos no existe', 'eventos' => $eventos];

        }
        return response()->json($respuesta, $respuesta['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function edit(Eventos $eventos)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $json = $request->input('json', null);
        $params_array = array_map('trim', json_decode($json, true));
        if (!empty($params_array)) {
            $validate = Validator::make($params_array, [
                'nombre' => 'required|alpha',
                'descripcion' => 'required|alpha',
                'fechaEvento' => 'required',
                'horaInicio' => 'required',
                'horaFinalizacion' => 'required',
                'horaInicio' => 'required',

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
                $evento = Eventos::where('id', $id)->update(
                    ['nombre' => $params_array['nombre']],
                    ['valor' => $params_array['valor']],
                    ['ocupacion' => $params_array['ocupacion']],
                    ['estado' => $params_array['estado']]);
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se Actualizo con exito el objeto',
                    'evento' => $evento);
            }
            return response()->json($data, $data['code']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eventos $eventos)
    {
        //
    }
}