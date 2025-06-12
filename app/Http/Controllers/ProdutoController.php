<?php

declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Enums\Categoria;
use App\Http\Requests\ProdutoStoreRequest;
use App\Http\Requests\ProdutoUpdateRequest;
use App\Http\Resources\ProdutoCollection;
use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProdutoController extends Controller
{
    public function index(Request $request): Response
    {
        if ($request->get('categoria')) {
            $produtos = get_cache_produtos_by_categoria(Categoria::from($request->get('categoria')));
        } else {
            $produtos = get_all_produtos_cache_by_categoria()->filter()->flatten();
        }

        if ($nome = $request->get('nome')) {
            $produtos = $produtos->filter(fn (Produto $produto) => str_contains($produto->nome, $nome))->values();
        }

        if ($request->get('preco') && $preco = preco_front_to_db($request->get('preco'))) {
            $produtos = $produtos->filter(function (Produto $produto) use ($preco) {
                $preco_db = (float) $produto->getRawOriginal('preco');
                $compara  = $preco_db == $preco;

                return $compara;
            })->values();
        }

        // $query = Produto::query();
        // $produtos = $query->paginate(10);
        $produtos = ProdutoCollection::make($produtos);

        return Inertia::render('Produto/Index', [
            'produtos'   => $produtos,
            'categorias' => Categoria::toSelect(),
            'filters'    => $request->only(['nome', 'preco']),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Produto/Edit', [
            'categorias' => Categoria::toSelect(),
        ]);
    }

    public function store(ProdutoStoreRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        $produto = Produto::create($data);

        rebuild_produtos_cache_by_categoria($produto->categoria);

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
            'produto'    => $produto,
            'categorias' => Categoria::toSelect(),
        ]);
    }

    public function update(ProdutoUpdateRequest $request, Produto $produto): RedirectResponse
    {
        $data = $request->safe()->except(['foto']);

        if ($request->hasFile('foto')) {
            if ($produto->foto) {
                Storage::disk('public')->delete($produto->foto);
            }
            $data['foto'] = $request->file('foto')->store('produtos', 'public');
        }

        $produto->update($data);

        rebuild_produtos_cache_by_categoria($produto->categoria);

        $request->session()->flash('produto.id', $produto->id);

        return redirect()->route('produtos.index');
    }

    public function destroy(Request $request, Produto $produto): RedirectResponse
    {
        $produto->delete();

        rebuild_produtos_cache_by_categoria($produto->categoria);

        return redirect()->route('produtos.index');
    }
}
