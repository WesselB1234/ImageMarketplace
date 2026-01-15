<?php 

namespace App\Models\Helpers;

class StringFormatter {

    public static function getDottedNumberStringFromNumber(int $number): string
    {
        return number_format($number, 0, ',', '.');
    }

    public static function getStringWithoutHtmlElements(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
    }
}