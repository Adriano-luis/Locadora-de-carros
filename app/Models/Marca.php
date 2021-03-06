<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'',
            'imagem' => 'required|file|mimes:jpeg,jpg,png'
        ];

        // unique:
        // 1) nome da tabela
        // 2) nome da coluna
        // 3)id do registro que sera desconsiderado na pesquisa
    }

    public function feedback(){
        return [
            'required' => 'O campo :attribute é requirido',
            'nome.unique' => 'O nome da marca já existe!',
            'image.mimes' => 'O arquivo precisa ter a extensão png, jpeg ou jpg'
        ];
    }

    public function modelos(){
        return $this->hasMany('App\Models\Modelo');
    }
}
