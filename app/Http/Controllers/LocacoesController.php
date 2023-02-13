<?php

namespace App\Http\Controllers;

use App\Models\Locacoes;
use Illuminate\Http\Request;
use App\Http\Repositories\LocacaoRepository;
use App\Http\Requests\LocacaoStoreRequest;


class LocacoesController extends Controller
{
    public function __construct(Locacoes $locacoes)
    {
        $this->$locacoes = $locacoes;
    }
    public function index(Request $request, LocacaoRepository $locacaoRepository, LocacaoStoreRequest $validation)
    {
        $LocacaoRepository = new LocacaoRepository($this->locacoes); 

        if($request->has('filtro')){
            $locacaoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, locacaoStoreRequest $validation)
    {
        $validation->validated();

        $locacao = $this->locacao->create([
            'cliente_id'=> $request->cliente_id,
            'carro_id'=> $request->carro_id,
            'data_inidio_periodo'=> $request->data_inidio_periodo,
            'data_final_previsto_periodo'=> $request-> data_final_previsto_periodo,
            'data_final_realizado_periodo'=> $request->data_final_realizado_periodo,
            'valor_diaria'=> $request->valor_diaria,
            'km_inicial'=> $request->km_inicial,
            'km_final'=> $request->km_final
        ]);

        return response()->json($locacao,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locacoes  $locacoes
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json('registro não foi encontrado');
        }

        return response()->json($locacao,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Locacoes  $locacoes
     * @return \Illuminate\Http\Response
     */
    public function edit(Locacoes $locacoes)
    {
        //
    }
    
    public function update(Request $request, int $id, LocacaoStoreRequest $validation)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
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
        
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locacoes  $locacoes
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null){
            return response()->json(['erro' => 'registro não encontrado'], 404);
        }

        $locacao->delete();
        return response()->json('Registro deletado com sucesso',201);
    }
}
