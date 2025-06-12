<?php

declare(strict_types = 1);

use App\Enums\Categoria;
use App\Models\Produto;
use Brick\Money\Money;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

if (! function_exists('preco_front_to_db')) {
    function preco_front_to_db($preco): ?int
    {
        $preco = Money::of($preco, 'BRL');
        $preco = $preco->getUnscaledAmount()->toInt();

        return $preco;
    }
}

if (! function_exists('preco_db_to_front')) {
    function preco_db_to_front($preco): ?float
    {
        $preco = (string) Money::ofMinor($preco, 'BRL')->getAmount();

        return (float) $preco;
    }
}

if (! function_exists('rebuild_produtos_cache_by_categoria')) {
    function rebuild_produtos_cache_by_categoria(Categoria $categoria)
    {
        $tag = app()->environment('testing') ? 'test' : 'produtos';
        Cache::tags($tag)->forget($categoria->value);
        $produtos = Produto::where('categoria', $categoria->value)->get();
        Cache::tags($tag)->put($categoria->value, $produtos);
    }
}

if (! function_exists('set_all_produtos_cache')) {
    function set_all_produtos_cache()
    {
        $tag = app()->environment('testing') ? 'test' : 'produtos';
        Cache::tags($tag)->flush();
        $produtos = Produto::all()->groupBy('categoria');

        foreach ($produtos as $categoria => $produtos) {
            $categoria = Categoria::from($categoria);
            Cache::tags($tag)->forever($categoria->value, $produtos);
        }
    }
}

if (! function_exists('get_cache_produtos_by_categoria')) {
    function get_cache_produtos_by_categoria(Categoria $categoria)
    {
        $tag = app()->environment('testing') ? 'test' : 'produtos';
        return Cache::tags($tag)->get($categoria->value, collect([]));
    }
}

if (! function_exists('get_all_produtos_cache_by_categoria')) {
    function get_all_produtos_cache_by_categoria(): Collection
    {
        $tag = app()->environment('testing') ? 'test' : 'produtos';
        $produtos = collect([]);

        foreach (Categoria::cases() as $categoria) {
            $produtos->put($categoria->value, Cache::tags($tag)->get($categoria->value));
        }

        return $produtos;
    }
}
