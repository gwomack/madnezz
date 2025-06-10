<?php

namespace App\Enums;

enum Categoria: string
{
    case Eletronicos = 'eletronicos';
    case Vestuario = 'vestuario';
    case Acessorios = 'acessorios';

    public function getLabel(): string
    {
        return match ($this) {
            self::Eletronicos => 'Eletrônicos',
            self::Vestuario => 'Vestuário',
            self::Acessorios => 'Acessórios',
            default => 'Eletrônicos',
        };
    }

    public static function toSelect(): array
    {
        return array_map(fn ($categoria) => [
            'value' => $categoria->value,
            'label' => $categoria->getLabel(),
        ], self::cases());
    }
}
