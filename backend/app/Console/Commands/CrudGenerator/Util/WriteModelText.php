<?php

namespace App\Console\Commands\CrudGenerator\Util;

class WriteModelText
{
    public static function render(string $text, array|object $parameters)
    {
        return str_replace(array_keys($parameters), array_values($parameters), $text);
    }
}
