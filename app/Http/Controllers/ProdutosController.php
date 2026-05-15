<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produto = Produto::all();

        return response()->json($produto, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ean' => 'required|string|max:255|unique:produtos',
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric',
            'quantidade' => 'required|integer',
            'categoria' => 'required|string|max:255',
        ]);

        $produto = Produto::create($request->all());

        return response()->json($produto, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto not found'], 404);
        } 

        return response()->json($produto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto not found'], 404);
        }

        $produto->update($request->all());

        return response()->json($produto, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json(['message' => 'Produto not found'], 404);
        }

        $produto->delete();
        return response()->json(['message' => 'Produto deleted successfully'], 200);
    }
}