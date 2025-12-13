<?php

namespace App\Enum;

enum ProductStatus: string
{
    case AVAILABLE = 'AVAILABLE';
    case OUT_OF_STOCK = 'OUT_OF_STOCK';
    case PRE_ORDER = 'PRE_ORDER';
}