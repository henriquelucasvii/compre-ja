<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItensPedido;
use App\Models\Pedido; 
use Illuminate\Support\Facades\DB;

class ItensPedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $itensPedidos = ItensPedido::with('itens.pedido')->get();

        return response()->json($itensPedidos, 200);
    }

    public function create()
    {
        return response()->json(['message' => 'Método não utilizado em API. Use o método POST para /itens-pedidos'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer|exists:users,id', 
            'data_pedido' => 'required|date',
            'itens'       => 'required|array|min:1',
            'itens.*.produto_id' => 'required|integer|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco'      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            
            $pedido = Pedido::create([
                'user_id'     => $request->input('user_id'),
                'data_pedido' => $request->input('data_pedido'),
            ]);

            $itens = [];
            foreach ($request->itens as $item) {
                $itens[] = new ItensPedido([
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco'      => $item['preco'],
                ]);
            }
            
            $pedido->itens()->saveMany($itens);

            DB::commit();

            return response()->json([
                'message' => 'Pedido criado com sucesso!',
                'pedido'  => $pedido->load('itens.produto')
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Erro ao criar o pedido.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pedido = Pedido::with('itens.produto')->find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        return response()->json($pedido, 200);
    }

    /**
     * Show the form for editing the specified resource.
     * Assim como o create, não é comum em APIs.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Método não utilizado em API.'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        $request->validate([
            'data_pedido' => 'sometimes|required|date',
        ]);

        try {
            $pedido->update($request->only('data_pedido'));

            return response()->json([
                'message' => 'Pedido atualizado com sucesso!',
                'pedido'  => $pedido
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar o pedido.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido não encontrado'], 404);
        }

        try { 

            $pedido->delete();

            return response()->json(['message' => 'Pedido excluído com sucesso!'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir o pedido.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}