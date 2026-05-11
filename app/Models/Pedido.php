<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable(['user_id', 'data_pedido', 'status'])]
class Pedido extends Model
{
    #[Override]
    public function usesTimestamps()
    {
        return parent::usesTimestamps();
    }
}
