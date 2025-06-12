<?php

declare(strict_types = 1);

namespace App\Models;

use App\Enums\Categoria;
use App\Http\Resources\ProdutoResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produto extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'preco',
        'descricao',
        'foto',
        'categoria',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id'        => 'integer',
            'categoria' => Categoria::class,
        ];
    }

    protected function preco(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => preco_db_to_front($value),
            set: fn (string $value) => preco_front_to_db($value),
        );
    }
}
