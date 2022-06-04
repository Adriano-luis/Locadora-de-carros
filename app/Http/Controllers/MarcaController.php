<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

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
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->get('atributos_modelos');
            $marcaRepository->selectAttibutosRegristrosRelacionados($atributos_modelos);

        }else
            $marcaRepository->selectAttibutosRegristrosRelacionados('modelos');
        
        if($request->has('filtro'))
            $marcaRepository->filtro($request->get('filtro'));

        if($request->has('atributos'))
            $marcaRepository->selectAtributos($request->get('atributos'));


        return response()->json($marcaRepository->getResultado(), 200);







        

        // $marcas = array();
        // if($request->has('atributos')){
        //     $atributos = $request->get('atributos');

        //     if($request->has('atributos_modelos')){
        //         $atributos_modelos = $request->get('atributos_modelos');
        //         $marcas = $this->marca->selectRaw($atributos)->with('modelos:id,'.$atributos_modelos)->get();
        //     }else{
        //         $marcas = $this->marca->selectRaw($atributos)->with('modelos')->get();
        //     }

        //     if($request->has('filtro')){
        //         $filtros = explode(';',$request->get('filtro'));
        //         foreach($filtros as $condicao){
        //             $c = explode(':',$condicao);
        //             $marcas = $marcas->where($c[0],$c[1],$c[2]);
        //         }
        //     }

        // }else{
        //     $marcas = $this->marca->with('modelos')->get();
        // }

        // return response()->json($marcas, 200);
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
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');
        $marca = $this->marca->create([
            'nome' => $request->get('nome'),
            'imagem' => $imagem_urn
        ]);

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
        $marca = $this->marca->with('modelos')->find($id);
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


        $marca->fill($request->all());
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens','public');
            $marca->imagem = $imagem_urn;
        }
        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if ($marca === null)
            return response()->json(['erro' => 'Impossível deletar. Recurso não encontrado'], 404);
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
    }
}
