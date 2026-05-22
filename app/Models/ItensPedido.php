<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pedido_id', 'produto_id', 'quantidade', 'preco'])]
class ItensPedido extends Model
{

    use SoftDeletes;

    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itens()
    {
        return $this->hasMany(ItensPedido::class, 'pedido_id');
    }
}
