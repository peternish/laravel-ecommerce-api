<?php

namespace App\Helpers;

class Helper
{
    static public function convertToPLN(int $value): float
    {
        return round($value/100, 2);
    }
}
