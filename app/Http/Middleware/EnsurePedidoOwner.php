<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Pedido;

class EnsurePedidoOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $pedidoId = $request->route('id');
        if ($pedidoId) {
            $pedidoId = Pedido::find($pedidoId);
            if (!$pedidoId || !$pedidoId->user_id !== $user->id) {
                return response()->json(['message' => 'Acesso negado. Você não é o dono deste pedido.'], 403);
            }
        }

        return $next($request);
    }
}
