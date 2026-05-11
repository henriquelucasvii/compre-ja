<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['pedido_id', 'produto_id', 'quantidade', 'preco'])]
class ItensPedido extends Model
{
    
}
