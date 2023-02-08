<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    public function __construct(Model $model){
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados(){
        $this->model = $this->model->with($atributos);
        //Motando a query
    }

    public function filtro($filtros){
        $filtros = explode(';',$filtros);

        foreach($filtros as $key =>$codicao){

            $c = $codicao->explode(';',$codicao);
            $this->model = $this->model->where($c[0],$c[1],$c[2]);
            //query Motada
        }
        
    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado(){
        return $this->model->get();
    }
}