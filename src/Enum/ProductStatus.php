<?php

namespace App\Enum;

enum ProductStatus: string
{
    case dispo = 'Disponible';
    case rupture = 'Rupture de stock';
    case preco = 'En précommande';
}
