<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacoes extends Model
{
    use HasFactory;
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inidio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];
}
