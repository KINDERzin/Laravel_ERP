<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    
    use HasFactory;

    // Nome da tabela
    protected $table = 'Clientes';

    // campos do db
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'endereco',
        'cidade',
        'estado',
        'cep'
    ];

    // desabilita o horário de "criação" (created_at, updated_at)
    public $timestamps = false;
}
