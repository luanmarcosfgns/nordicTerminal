<?php

namespace App\Console\Commands\CrudGenerator\Util;

use Illuminate\Support\Facades\DB;

class DatabaseTest
{
    public function connected()
    {
        try {
            DB::select('select COLUMN_NAME as name from information_schema.COLUMNS');
            echo "Connected Database ☑️ " . PHP_EOL;
        } catch (\Exception $e){
            echo "Error: " .$e->getMessage().'Line: '.$e->getLine().' File: '.$e->getFile(). PHP_EOL;
        }

    }

}
