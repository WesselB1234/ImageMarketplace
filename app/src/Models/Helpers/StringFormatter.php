<?php 

namespace App\Models\Helpers;

class StringFormatter {

    public static function getDottedNumberStringFromNumber(int $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}