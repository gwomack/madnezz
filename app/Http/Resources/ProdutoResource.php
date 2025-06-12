<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->getKey(),
            'nome'       => $this->nome,
            'preco'      => $this->preco,
            'descricao'  => $this->descricao,
            'foto'       => $this->foto,
            'categoria'  => $this->categoria,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // 'deleted_at'      => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
