<?php

declare(strict_types = 1);

namespace Database\Factories;

use App\Models\Produto;
use App\Enums\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Produto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nome'      => fake()->name(),
            'preco'     => fake()->numberBetween(1, 100, true),
            'descricao' => fake()->sentence(),
            'foto'      => fake()->imageUrl(),
            'categoria' => fake()->randomElement(Categoria::cases()),
        ];
    }
}
