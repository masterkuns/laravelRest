<?php

namespace App\Http\Controllers;

use App\Models\asistentes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsistentesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Actividades = asistentes::all();
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
                'idUsuario' => 'required',
                'idEvento' => 'required',

            ]);
            if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'No se ha podido crear el asitentes',
                    'errors' => $validate->errors(),
                );
            } else {
                $actividades = new Asistentes();
                $actividades->nombre = $params_array['idUsuario'];
                $actividades->descripcion = $params_array['idEvento'];

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
     * @param  \App\Models\asistentes  $asistentes
     * @return \Illuminate\Http\Response
     */
    public function show(asistentes $asistentes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\asistentes  $asistentes
     * @return \Illuminate\Http\Response
     */
    public function edit(asistentes $asistentes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\asistentes  $asistentes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, asistentes $asistentes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\asistentes  $asistentes
     * @return \Illuminate\Http\Response
     */
    public function destroy(asistentes $asistentes)
    {
        //
    }
}