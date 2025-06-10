<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Produto;
use App\Enums\Categoria;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProdutoStoreRequest;
use App\Http\Requests\ProdutoUpdateRequest;

class ProdutoController extends Controller
{
    public function index(Request $request): Response
    {
        $produtos = Produto::paginate(10);

        return Inertia::render('Produto/Index', [
            'produtos' => $produtos
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Produto/Create', [
            'categorias' => Categoria::toSelect(),
        ]);
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
