<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Received = 'received';
    case Calculated = 'calculated';

    public function label(): string
    {
        return match ($this) {
            self::Received => 'Recebido',
            self::Calculated => 'Calculado',
        };
    }
}
