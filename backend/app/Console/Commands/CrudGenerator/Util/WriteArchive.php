<?php

namespace App\Console\Commands\CrudGenerator\Util;

class WriteArchive
{
    public static function now(string $data,string $name, string $dir):bool
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $filePath = rtrim($dir, '/') . '/' . $name;
        $result = file_put_contents($filePath, $data);
        return $result !== false;
    }
}
