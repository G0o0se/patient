<?php

namespace App\Utils;

class CodeGenerator
{
    static private $string = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function generateCode($length = 10): string
    {
        $charactersLength = strlen(self::$string);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= self::$string[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
