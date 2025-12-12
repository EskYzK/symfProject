<?php

namespace App\Enum;

enum OrderStatus: string
{
    case prepa = 'En préparation';
    case expe = 'Expédiée';
    case liv = 'Livrée';
}
