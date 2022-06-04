<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id,'.$request->get('atributos_marca');
            $modeloRepository->selectAttibutosRegristrosRelacionados($atributos_marca);
        }else
            $modeloRepository->selectAttibutosRegristrosRelacionados('marca');

        if($request->has('filtro'))
            $modeloRepository->filtro($request->get('filtro'));

        if($request->has('atributos'))
            $modeloRepository->selectAtributos($request->get('atributos'));

        return response()->json($modeloRepository->getResultado(), 200);








        
        // $modelos = array();
        // if($request->has('atributos')){
        //     $atributos = $request->get('atributos');

        //     if($request->has('atributos_marca')){
        //         $atributos_marca = $request->get('atributos_marca');
        //         $modelos = $this->modelo->selectRaw($atributos)->with('marca:id,'.$atributos_marca)->get();
        //     }else{
        //         $modelos = $this->modelo->selectRaw($atributos)->with('marca')->get();
        //     }

        //     if($request->has('filtro')){
        //         $filtros = explode(';',$request->get('filtro'));
        //         foreach($filtros as $condicao){
        //             $c = explode(':',$condicao);
        //             $modelos = $modelos->where($c[0],$c[1],$c[2]);
        //         }
        //     }

        // }else{
        //     $modelos = $this->modelo->with('marca')->get();
        // }

        // return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');
        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null)
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404);

        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null)
            return repsonse()->json(['Impossível atualizar. Recurso não encontrado'], 200, 404);
        
        if($request->method == 'PATCH'){
            $regrasDinamicas = array();

            foreach($this->modelo->rules() as $input => $rule){
                if(array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $rule;
                
            }

            $request->validate($regrasDinamicas, $this->modelo->feedback());
        }else
            $request->validate($this->modelo->rules(), $this->modelo->feedback());
        

        $modelo->fill($request->all());  
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens/modelos','public');
            $modelo->imagem = $imagem_urn;
        }
        $modelo->save(); //se save tem o id definifo ele funciona como update

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null)
            return response()->json(['erro' => 'Impossível deletar. Recurso não encontrado'], 404);
        
        Storage::disk('public')->delete($modelo->imagem);
        $modelo->delete();

        return response()->json(['msg' => 'O modelo foi removida com sucesso!'], 200);
    }
}
