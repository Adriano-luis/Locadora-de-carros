<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('atributos_locacoes')){
            $atributos_locacoes = 'locacoes:id,'.$request->get('atributos_locacoes');
            $clienteRepository->selectAttibutosRegristrosRelacionados($atributos_locacoes);

        }else
            $clienteRepository->selectAttibutosRegristrosRelacionados('locacoes');
        
        if($request->has('filtro'))
            $clienteRepository->filtro($request->get('filtro'));

        if($request->has('atributos'))
            $clienteRepository->selectAtributos($request->get('atributos'));


        return response()->json($clienteRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente = $this->cliente->create([
            'nome' => $request->get('nome')
        ]);

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->with('locacoes')->find($id);
        if ($cliente === null)
            return response()->json(['erro' => 'Recurso não encontrado'], 404);

        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null)
            return response()->json(['erro' => 'Impossível atualizar. Recurso não encontrado'], 404);

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($cliente->rules() as $input => $rule) {
                if(array_key_exists($input, $request->all()))
                    $regrasDinamicas[$input] = $rule;
            }
            $request->validate($regrasDinamicas, $cliente->feedback());
        }else
            $request->validate($cliente->rules(), $cliente->feedback());


        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if ($cliente === null)
            return response()->json(['erro' => 'Impossível deletar. Recurso não encontrado'], 404);

        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi removido com sucesso!'], 200);
    }
}
