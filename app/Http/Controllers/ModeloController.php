<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;
use App\Http\Requests\ModeloStoreRequest;
use App\Repositories\ModeloRepository;


class ModeloController extends Controller
{
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    public function index(Request $request)
    {   
        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id' .$request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marcas);
        }else{
            $modelo = $this->modelo->with('marca');
        }
        if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $modeloRepository->selectAtributos($request->atributos);
        }

        return response()->json($modeloRepository->getResultado(), 201);
    }
//Não Faz Sentido - 2
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
    public function store(Request $request, ModeloStoreRequest $validation)
    {

        $validation->validated();
    
        $imagem = $request->file('imagem');
        $imagem_rnu = $imagem->store('imagem/modelo', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_rnu,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 201);
    }

 
    public function show(int $id)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo === null){
            return response()->json('Resgistro não encontrado', 404);
        }
            return response()->json($modelo,200);
    }

  
    public function edit(Modelo $modelo)
    {
        //
    }


    public function update(Request $request, int $id, ModeloStoreRequest $validation)
    {
        $modelo = $this->modelo->with('marca')->find($id);

        if($modelo === null){
            return response()->json('Resgistro não foi encontrado', 404);
        }

        $validation->validated();

        $modelo = $this->modelo->update();
            return response()->json($modelo, 201);        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json('Resgistro não foi encontrado');
        }

        $modelo -> delete();
        return response()->json(["msg" =>"Registro Deletado com Sucesso" ]);
    }
}
