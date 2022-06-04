<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao){
        $this->locacao = $locacao;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        // if($request->has('atributos_modelo')){
        //     $atributos_modelo = 'modelo:id,'.$request->get('atributos_modelo');
        //     $locacaoRepository->selectAttibutosRegristrosRelacionados($atributos_modelo);

        // }else
        //     $locacaoRepository->selectAttibutosRegristrosRelacionados('modelo');
        
        if($request->has('filtro'))
            $locacaoRepository->filtro($request->get('filtro'));

        if($request->has('atributos'))
            $locacaoRepository->selectAtributos($request->get('atributos'));


        return response()->json($locacaoRepository->getResultado(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->get('cliente_id'),
            'carro_id' => $request->get('carro_id'),
            'data_inicio_periodo' => $request->get('data_inicio_periodo'),
            'data_final_previsto_periodo' => $request->get('data_final_previsto_periodo'),
            'data_final_realizado_periodo' => $request->get('data_final_realizado_periodo'),
            'valor_diaria' => $request->get('valor_diaria'),
            'km_inicial' => $request->get('km_inicial'),
            'km_final' => $request->get('km_final')
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null)
            return response()->json(['erro' => 'Recurso não encontrado'], 404);

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null)
            return response()->json(['erro' => 'Impossível atualizar. Recurso não encontrado'], 404);

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($locacao->rules() as $input => $rule) {
                if(array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $rule;
            }
            $request->validate($regrasDinamicas, $locacao->feedback());
        }else
            $request->validate($locacao->rules(), $locacao->feedback());


        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if ($locacao === null)
            return response()->json(['erro' => 'Impossível deletar. Recurso não encontrado'], 404);

        $locacao->delete();
        return response()->json(['msg' => 'O registro foi removido com sucesso!'], 200);
    }
}
