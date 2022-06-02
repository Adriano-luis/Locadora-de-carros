<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca){
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $marcas = Marca::all();
        $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    //@param  \App\Models\Marca  $marca
    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null)
            return response()->json(['erro' => 'Recurso não encontrado'], 404);
        return response()->json($marca, 200);
    }

    //@param  \App\Models\Marca  $marca
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $marca->update($request->all());
        $marca = $this->marca->find($id);

        if ($marca === null)
            return response()->json(['erro' => 'Impossível atualizar. Recurso não encontrado'], 404);

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($marca->rules() as $input => $rule) {
                if(array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $rule;
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        }else
            $request->validate($marca->rules(), $marca->feedback());
            
        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    //@param  \App\Models\Marca  $marca
    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $marca->delete();
        $marca = $this->marca->find($id);
        if ($marca === null)
            return response()->json(['erro' => 'Ipossível deletar. Recurso não encontrado'], 404);
        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}