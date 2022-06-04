<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules(){
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:jpeg,jpg,png',
            'numero_portas' => 'required|integer|digits_between:1,4',
            'lugares' => 'required|integer|digits_between:1,7',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'

        ];

    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é requirido',
            'nome.unique' => 'O nome do modelo já existe!',
            'nome.min' => 'O nome precisa ter pelo menso 3 caracteres',
            'image.mimes' => 'O arquivo precisa ter a extensão png, jpeg ou jpg',
            'numero_portas.digits_between' => 'O número de portas deve ser entre 1 e 4',
            'lugares.digits_between' => 'O número de lugares deve ser entre 1 e 7',
            'air_bag.boolean' => 'Deve ser true ou false',
            'abs.boolean' => 'Deve ser true ou false'
        ];
    }

    public function marca(){
        return $this->belongsTo('App\Models\Marca');
    }

    public function carros(){
        return $this->hasMany('App\Models\Carro');
    }

}
