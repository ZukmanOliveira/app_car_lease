<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Http\Requests\MarcaStoreRequests;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
 
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca); 

        if($request->has('atributos_modelo')){
            $atributos_modelos = 'modelos:id'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRigistrosRelacionados($atributos_modelos);
        }else{
            $marca = $this->marca->with('modelos');
        }

        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultado(), 200);
    }

    public function store(Request $request, MarcaStoreRequests $validation)
    {
        $validation->validated();

        //$marca = $this->marca->create($request->all());
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem','public');
        
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' =>$imagem_urn
        ]);

        return response()->json('Cadastrado Com Sucesso', 200);
    }

    public function show(int $id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        
        if($marca === null)
        {
            return response()->json('Recurso Pesquisado nao existe',404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function edit(Marca $marca)
    {
        //
    }

    //Em manunteção para implementar a alteração pelo metodo patch Aula 302
    public function update(Request $request, int $id, MarcaStoreRequests $validation)
    {
        $marca = $this->marca->find($id);
           
        if($marca === null)
        {
            return response()->json('There is no record to be updated',404);
        }

        if($request->REQUEST_METHOD === 'PATCH'){
            
            //percorredo todas as regras definidas no Request
            foreach($validation->validated() as $input => $regras){
                
            }
            $validation->validated();

        }else {
            $validation->validated();
        }
   
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem','public');
        
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

            return response()->json('change completed', 200);
    }

//verificar se a imagem foi apagada no diretorio
    public function destroy(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null)
        {
            return response()->json('There is no record to be deleted', 404);
        }
        
        Storage::disk('public')->delete($marca->imagem);
        
        $marca->delete();
        
        return response()->json('Deletado Com Sucesso', 200);
    }
}
