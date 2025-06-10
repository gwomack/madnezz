<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoStoreRequest;
use App\Http\Requests\ProdutoUpdateRequest;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class ProdutoController extends Controller
{
    public function index(Request $request): Response
    {
        $produtos = Produto::all();

        return Inertia::render('Produto/Index', [
            'produtos' => $produtos,
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Produto/Create');
    }

    public function store(ProdutoStoreRequest $request): RedirectResponse
    {
        $produto = Produto::create($request->validated());

        $request->session()->flash('produto.id', $produto->id);

        return redirect()->route('produtos.index');
    }

    public function show(Request $request, Produto $produto): Response
    {
        return Inertia::render('Produto/Show', [
            'produto' => $produto,
        ]);
    }

    public function edit(Request $request, Produto $produto): Response
    {
        return Inertia::render('Produto/Edit', [
            'produto' => $produto,
        ]);
    }

    public function update(ProdutoUpdateRequest $request, Produto $produto): RedirectResponse
    {
        $produto->update($request->validated());

        $request->session()->flash('produto.id', $produto->id);

        return redirect()->route('produtos.index');
    }

    public function destroy(Request $request, Produto $produto): RedirectResponse
    {
        $produto->delete();

        return redirect()->route('produtos.index');
    }
}
