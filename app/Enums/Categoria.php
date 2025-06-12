<?php

declare(strict_types = 1);

namespace App\Enums;

enum Categoria: string
{
    case eletronicos = 'eletronicos';
    case vestuario   = 'vestuario';
    case acessorios  = 'acessorios';

    public function getLabel(): string
    {
        return match ($this) {
            self::eletronicos => 'Eletrônicos',
            self::vestuario   => 'Vestuário',
            self::acessorios  => 'Acessórios',
            default           => 'Eletrônicos',
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
