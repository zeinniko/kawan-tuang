<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup: string implements HasLabel
{
    case OrderFulfillment = 'ORDER FULFILLMENT';
    case Catalog = 'KATALOG PRODUK';
    case StoreInventory = 'STORE INVENTORY';
    case Compliance = 'USERS COMPLIANCE';
    case Marketing = 'MARKETING FEEDBACK';

    public function getLabel(): string
    {
        return $this->value;
    }
}