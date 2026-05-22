<?php

namespace App\Http\Controllers;

use App\Models\ItensPedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('itens.produto')->get();

        return response()->json($pedidos, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|integer|exists:users,id',
            'data_pedido'       => 'required|date',
            'itens'             => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $pedido = Pedido::create([
                'user_id'     => $request->user_id,
                'data_pedido' => $request->data_pedido,
                'status'      => 'pendente',
            ]);

            foreach ($request->itens as $item) {
                ItensPedido::create([
                    'pedido_id'  => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco'      => $item['preco'],
                ]);
            }

            DB::commit();

            return response()->json($pedido->load('itens.produto'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar pedido: ' . $e->getMessage());

            return response()->json(['message' => 'Erro ao criar pedido'], 500);
        }
    }

    public function show(int $id)
    {
        $pedido = Pedido::with('itens.produto')->find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        return response()->json($pedido, 200);
    }

    public function update(Request $request, int $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        $request->validate([
            'status' => 'sometimes|string|max:50',
            'data_pedido' => 'sometimes|date',
        ]);

        $pedido->update($request->only(['status', 'data_pedido']));

        return response()->json($pedido->load('itens.produto'), 200);
    }

    public function destroy(int $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        $pedido->itens()->delete();
        $pedido->delete();

        return response()->json(['message' => 'Pedido deletado com sucesso'], 200);
    }
}