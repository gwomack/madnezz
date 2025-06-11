<?php

use App\Models\Produto;
use App\Enums\Categoria;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Brick\Money\Money;

if (!function_exists('preco_front_to_db')) {
    function preco_front_to_db($preco): ?int {
      $preco = Money::of($preco, 'BRL');
      $preco = $preco->getUnscaledAmount()->toInt();
      return $preco;
    }
}

if (!function_exists('preco_db_to_front')) {
    function preco_db_to_front($preco): ?string {
      $preco = Money::ofMinor($preco, 'BRL')->getAmount();
      return $preco;
    }
}

if (!function_exists('rebuild_produtos_cache_by_categoria')) {
    function rebuild_produtos_cache_by_categoria(Categoria $categoria) {
      Cache::tags('produtos')->forget($categoria->value);
      $produtos = Produto::where('categoria', $categoria->value)->get();
      Cache::tags('produtos')->put($categoria->value, $produtos);
    }
}

if (!function_exists('set_all_produtos_cache')) {
    function set_all_produtos_cache() {
      Cache::flush();
      $produtos = Produto::all()->groupBy('categoria');
      foreach ($produtos as $categoria => $produtos) {
        $categoria = Categoria::from($categoria);
        Cache::tags('produtos')->forever($categoria->value, $produtos);
      }
    }
}

if (!function_exists('get_cache_produtos_by_categoria')) {
    function get_cache_produtos_by_categoria(Categoria $categoria) {
        return Cache::tags('produtos')->get($categoria->value, collect([]));
    }
}

if (!function_exists('get_all_produtos_cache_by_categoria')) {
    function get_all_produtos_cache_by_categoria(): Collection {
      $produtos = collect([]);
      foreach (Categoria::cases() as $categoria) {
        $produtos->put($categoria->value, Cache::tags('produtos')->get($categoria->value));
      }
      return $produtos;
    }
}