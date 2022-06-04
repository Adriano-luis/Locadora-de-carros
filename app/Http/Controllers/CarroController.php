<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    public function __construct(Carro $carro){
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->get('atributos_modelo');
            $carroRepository->selectAttibutosRegristrosRelacionados($atributos_modelo);

        }else
            $carroRepository->selectAttibutosRegristrosRelacionados('modelo');
        
        if($request->has('filtro'))
            $carroRepository->filtro($request->get('filtro'));

        if($request->has('atributos'))
            $carroRepository->selectAtributos($request->get('atributos'));


        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $carro = $this->carro->create([
            'modelo_id' => $request->get('modelo_id'),
            'placa' => $request->get('placa'),
            'disponivel' => $request->get('disponivel'),
            'km' => $request->get('km')
        ]);

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if ($carro === null)
            return response()->json(['erro' => 'Recurso não encontrado'], 404);

        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null)
            return response()->json(['erro' => 'Impossível atualizar. Recurso não encontrado'], 404);

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($carro->rules() as $input => $rule) {
                if(array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $rule;
            }
            $request->validate($regrasDinamicas, $carro->feedback());
        }else
            $request->validate($carro->rules(), $carro->feedback());


        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if ($carro === null)
            return response()->json(['erro' => 'Impossível deletar. Recurso não encontrado'], 404);

        $carro->delete();
        return response()->json(['msg' => 'O carro foi removido com sucesso!'], 200);
    }
}
