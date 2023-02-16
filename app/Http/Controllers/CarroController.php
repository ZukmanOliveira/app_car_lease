<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\Http\Requests\CarroStoreRequests;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
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
            $atributos_modelo = 'modelo:id'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        }else{
            $carro = $this->carro->with('modelos');
        }

        if($request->has('filtro')){
            $carroRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
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

    
    public function store(Request $request, CarroStoreRequest $validation)
    {
        $validation->validated();

        $carro = $this->carro->create([
            'carro' => $request->nome,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);

        return response()->json($carro,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show(Carro $carro, int $id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if($carro === null ){
            return response()->json(['erro' => 'recurso não existe']);
        }

        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function edit(Carro $carro)
    {
        //
    }


    public function update(Request $request, CarroStoreRequest $validation, int $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){
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
        
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    
    public function destroy(int $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'registro não encontrado'], 404);
        }

        $carro->delete();
        return response()->json('Registro deletado com sucesso',201);
    }
}
