<?php

declare(strict_types = 1);

use App\Models\Produto;
use App\Enums\Categoria;
use Illuminate\Support\Facades\Cache;
use JMac\Testing\Traits\AdditionalAssertions;
use Inertia\Testing\AssertableInertia as Assert;

uses(AdditionalAssertions::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(Illuminate\Foundation\Testing\WithFaker::class);


pest()->group('produtos');


test('index displays view', function () {
    $size = 3;
    $produtos = Produto::factory($size)->create();
    set_all_produtos_cache();

    $response = login()->get(route('produtos.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Produto/Index')
    ->has('produtos', function (Assert $page) use ($produtos, $size) {
        $page->has('data', $size, function (Assert $page) use ($produtos) {
            $data = $page->toArray()['props'];
            // dd($data);
            $produto = $produtos->where('id', $data['id'])->first();
            $page->where('id', $produto->id);
            $page->where('nome', $produto->nome);
            $page->where('preco', (int) $produto->preco);
            $page->where('descricao', $produto->descricao);
            $page->where('foto', $produto->foto);
            $page->where('categoria', $produto->categoria);
            $page->etc();
        });
    }));
});

test('create displays view', function () {
    $response = login()->get(route('produtos.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Produto/Edit'));
});

test('store uses form request validation', function () {
    $this->assertActionUsesFormRequest(
        App\Http\Controllers\ProdutoController::class,
        'store',
        App\Http\Requests\ProdutoStoreRequest::class
    );
});

test('store saves and redirects', function () {
    $produto = Produto::factory()->make([
        'foto' => null,
    ]);
    $produto = $produto->toArray();

    $response = login()->post(route('produtos.store'), $produto);

    $produtos = Produto::query()
        ->where('nome', $produto['nome'])
        ->where('preco', preco_front_to_db($produto['preco']))
        ->get();
    expect($produtos)->toHaveCount(1);
    $produto = $produtos->first();

    $response->assertRedirect(route('produtos.index'));
    $response->assertSessionHas('produto.id', $produto->id);
});

test('show displays view', function () {
    $produto = Produto::factory()->create();

    $response = login()->get(route('produtos.show', $produto));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Produto/Show')
    ->has('produto', function (Assert $page) use ($produto) {
        $page->where('id', $produto->id);
        $page->where('nome', $produto->nome);
        $page->where('preco', (int) $produto->preco);
        $page->where('descricao', $produto->descricao);
        $page->where('foto', $produto->foto);
        $page->where('categoria', $produto->categoria);
        $page->etc();
    }));
});

test('edit displays view', function () {
    $produto = Produto::factory()->create();

    $response = login()->get(route('produtos.edit', $produto));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Produto/Edit')
    ->has('produto', function (Assert $page) use ($produto) {
        $page->where('id', $produto->id);
        $page->where('nome', $produto->nome);
        $page->where('preco', (int) $produto->preco);
        $page->where('descricao', $produto->descricao);
        $page->where('foto', $produto->foto);
        $page->where('categoria', $produto->categoria);
        $page->etc();
    }));
});

test('update uses form request validation', function () {
    $this->assertActionUsesFormRequest(
        App\Http\Controllers\ProdutoController::class,
        'update',
        App\Http\Requests\ProdutoUpdateRequest::class
    );
});

test('update redirects', function () {
    $produto = Produto::factory()->create();
    $nome    = fake()->name();
    $preco   = fake()->randomFloat(2, 0, 100);
    $descricao = fake()->sentence();
    $categoria = fake()->randomElement(Categoria::toSelect())["value"];

    $response = login()->put(route('produtos.update', $produto), [
        'nome'  => $nome,
        'preco' => $preco,
        'descricao' => $descricao,
        'categoria' => $categoria,
    ]);

    $produto->refresh();

    $response->assertRedirect(route('produtos.index'));
    $response->assertSessionHas('produto.id', $produto->id);

    expect($produto->nome)->toBe($nome);
    expect($produto->preco)->toBe($preco);
    expect($produto->descricao)->toBe($descricao);
    expect($produto->categoria)->toBe(Categoria::from($categoria));
});

test('destroy deletes and redirects', function () {
    $produto = Produto::factory()->create();

    $response = login()->delete(route('produtos.destroy', $produto));

    $response->assertRedirect(route('produtos.index'));

    $this->assertSoftDeleted($produto);
});

test('checks if cache was updated when produto is stored', function () {
    $produtos = Produto::factory(2)->make([
        'foto' => null,
    ]);

    foreach($produtos as $produto) {
        $response = login()->post(route('produtos.store'), $produto->toArray());
        $produtos_cat = Produto::where('categoria', $produto->categoria)->get();
        expect(Cache::tags('test')->get($produto->categoria->value))
        ->toEqual($produtos_cat);
    }
});

test('checks if cache was updated when produto is updated', function () {
    $produtos = Produto::factory(2)->create([
        'foto' => null,
    ]);

    foreach($produtos as $produto) {
        $response = login()->put(route('produtos.update', $produto), $produto->toArray());
        $produtos_cat = Produto::where('categoria', $produto->categoria)->get();
        expect(Cache::tags('test')->get($produto->categoria->value))
        ->toEqual($produtos_cat);
    }
});

test('checks if cache was updated when produto was deleted', function () {
    $produtos = Produto::factory(2)->create([
        'categoria' => $categoria = Categoria::from('eletronicos'),
        'foto' => null,
    ]);

    $response = login()->delete(route('produtos.destroy', $produtos->first()->id));
    $produtos_cat = Produto::where('categoria', $categoria->value)->get();
    expect(Cache::tags('test')->get($categoria->value))
    ->toHaveCount(1);
});