<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';

    protected $fillable = [
        'cliente_id', 
        'carro_id', 
        'data_inicio_periodo', 
        'data_final_previsto_periodo',
        'data_final_realizado_periodo', 
        'valor_diaria', 
        'km_inicial', 
        'km_final'
    ];

    public function rules(){
        return [
            'cliente_id' => 'required',//exists:marcas,id',
            'carro_id' => 'required',//|unique:modelos,nome,'.$this->id.'|min:3',
            'data_inicio_periodo' => 'required',//|file|mimes:jpeg,jpg,png',
            'data_final_previsto_periodo' => 'required',//|integer|digits_between:1,4',
            'data_final_realizado_periodo' => 'required',//|integer|digits_between:1,7',
            'valor_diaria' => 'required',//|boolean',
            'km_inicial' => 'required',//|boolean',
            'km_final' => 'required'

        ];

    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é requirido'
        ];
    }

    public function marca(){
        return $this->belongsTo('App\Models\Marca');
    }

    public function carros(){
        return $this->hasMany('App\Models\Carro');
    }
}
