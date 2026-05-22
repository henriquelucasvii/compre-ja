<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Override;
use App\Models\ItensPedido;

#[Fillable(['user_id', 'data_pedido', 'status'])]
class Pedido extends Model
{
    #[Override]
    public function usesTimestamps()
    {
        return parent::usesTimestamps();
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function itens()
    {
        return $this->hasMany(ItensPedido::class, 'pedido_id');
    }
}
