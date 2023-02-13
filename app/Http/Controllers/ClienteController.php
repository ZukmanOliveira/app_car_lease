<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;
use App\Http\Request\ClienteStoreRequest;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
  
    public function index(Request $request, ClienteRepository $clienteRepository)
    {
        $clienteRepository = new clienteRepository($this->carro); 

        if($request->has('filtro')){
            $clienteRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResultado(), 200);
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
    public function store(Request $request, ClienteStoreRequest $validation )
    {
        $validation->validated();

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);

        return response()->json($cliente,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $cliente = $this->cliente->with('modelo')->find($id);
        if($cliente === null ){
            return response()->json(['erro' => 'recurso não existe']);
        }

        return response()->json($cliente, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id, ClienteStoreRequest $validation)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Não foi possivel encontro registro que deseja atualizar']);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach($validation->rules() as  $inpunt => $regras){
                if(arrey_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regras;
                }
            }
            $request->validated($regrasDinamicas);
        }else{
            $request->validated($validation->rules());
        }
        
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
    public function destroy(int $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Registro não encontrado']);
        }
        $cliente->delete();
        
        return response()->json('Registro deletado com sucesso', 200);
    }
}
