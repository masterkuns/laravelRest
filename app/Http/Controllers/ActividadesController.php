<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActividadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Actividades = Actividades::all();
        $respuesta = response()->json(['code' => 200, 'status' => 'succes', 'Actividades' => $Actividades]);

        return $respuesta;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $params_array = array_map('trim', json_decode($json, true));
        if (!empty($params_array)) {
            $validate = Validator::make($params_array, [
                'nombre' => 'required',
                'horaInicio' => 'required',
                'horaFinalizacion' => 'required',
                'monitor' => 'required',
                'idUsuario' => 'required',

            ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se ha podido crear el actividades',
                    'errors' => $validate->errors(),
                );
            } else {
                $actividades = new Actividades();
                $actividades->nombre = $params_array['nombre'];
                $actividades->descripcion = $params_array['horaInicio'];
                $actividades->direccion = $params_array['horaFinalizacion'];
                $actividades->valor = $params_array['monitor'];
                $actividades->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se ha creado con exito el objeto',
                    'actividades' => $actividades);
            }
            return response()->json($data, $data['code']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Actividades  $actividades
     * @return \Illuminate\Http\Response
     */
    public function show(Actividades $actividades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Actividades  $actividades
     * @return \Illuminate\Http\Response
     */
    public function edit(Actividades $actividades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Actividades  $actividades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actividades $actividades, $id)
    {
        $json = $request->input('json', null);
        $params_array = array_map('trim', json_decode($json, true));
        if (!empty($params_array)) {
            $validate = Validator::make($params_array, [
                'nombre' => 'required',
                'horaInicio' => 'required',
                'horaFinalizacion' => 'required',
                'monitor' => 'required',
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
                $actividades = Actividades::where('id', $id)->update(
                    ['nombre' => $params_array['nombre']],
                    ['horaInicio' => $params_array['horaInicio']],
                    ['horaFinalizacion' => $params_array['horaFinalizacion']],
                    ['monitor' => $params_array['monitor']]

                );
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Se Actualizo con exito el objeto',
                    'actividades' => $actividades);
            }
            return response()->json($data, $data['code']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Actividades  $actividades
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actividades $actividades, $id)
    {
        $actividades = Actividades::find($id);

        if (is_object($actividades) && !empty($actividades)) {
            $actividades->delete();
            $data = [
                'code' => 200,
                'status' => 'success',
                'actividades' => $actividades,
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