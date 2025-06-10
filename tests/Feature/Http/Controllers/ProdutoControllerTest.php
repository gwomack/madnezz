<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Produto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProdutoController
 */
final class ProdutoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $produtos = Produto::factory()->count(3)->create();

        $response = $this->get(route('produtos.index'));

        $response->assertOk();
        $response->assertViewIs('produto.index');
        $response->assertViewHas('produtos');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('produtos.create'));

        $response->assertOk();
        $response->assertViewIs('produto.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProdutoController::class,
            'store',
            \App\Http\Requests\ProdutoStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $nome = fake()->word();
        $preco = fake()->word();

        $response = $this->post(route('produtos.store'), [
            'nome' => $nome,
            'preco' => $preco,
        ]);

        $produtos = Produto::query()
            ->where('nome', $nome)
            ->where('preco', $preco)
            ->get();
        $this->assertCount(1, $produtos);
        $produto = $produtos->first();

        $response->assertRedirect(route('produtos.index'));
        $response->assertSessionHas('produto.id', $produto->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $produto = Produto::factory()->create();

        $response = $this->get(route('produtos.show', $produto));

        $response->assertOk();
        $response->assertViewIs('produto.show');
        $response->assertViewHas('produto');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $produto = Produto::factory()->create();

        $response = $this->get(route('produtos.edit', $produto));

        $response->assertOk();
        $response->assertViewIs('produto.edit');
        $response->assertViewHas('produto');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProdutoController::class,
            'update',
            \App\Http\Requests\ProdutoUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $produto = Produto::factory()->create();
        $nome = fake()->word();
        $preco = fake()->word();

        $response = $this->put(route('produtos.update', $produto), [
            'nome' => $nome,
            'preco' => $preco,
        ]);

        $produto->refresh();

        $response->assertRedirect(route('produtos.index'));
        $response->assertSessionHas('produto.id', $produto->id);

        $this->assertEquals($nome, $produto->nome);
        $this->assertEquals($preco, $produto->preco);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $produto = Produto::factory()->create();

        $response = $this->delete(route('produtos.destroy', $produto));

        $response->assertRedirect(route('produtos.index'));

        $this->assertSoftDeleted($produto);
    }
}
