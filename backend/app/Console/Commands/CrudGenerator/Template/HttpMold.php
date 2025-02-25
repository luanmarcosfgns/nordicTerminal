<?php

namespace App\Console\Commands\CrudGenerator\Template;

use App\Console\Commands\CrudGenerator\Util\WriteArchive;
use App\Console\Commands\CrudGenerator\Util\WriteModelText;
use Illuminate\Support\Facades\DB;

class HttpMold
{

    public static function buildHttp(string $table)
    {
        $beLongTo = "";
        $database = getenv('DB_DATABASE');

        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length,IS_NULLABLE as is_nullable
from information_schema.COLUMNS
where TABLE_NAME = ':table'
    and TABLE_SCHEMA = ':database'
    and COLUMN_NAME not in ('created_at','updated_at','id')
    ";

        $sql = str_replace(':table', $table, $sql);
        $sql = str_replace(':database', $database, $sql);

        $columns = DB::select($sql);
        if (empty($columns)) {
            echo 'The table not exists' . PHP_EOL;
            return false;
        }

        $columns = self::setColumns($columns);
        if (!$columns) {
            echo 'The table not exists' . PHP_EOL;
            return false;
        }


        $writeModelText = WriteModelText::render(self::mold(), [
            ':table' => $table,
            ':columns' => $columns,
            ':description' => strtoupper($table),
        ]);
        $dir = base_path() . '/docs';

        $filename = $table . '.http';
        WriteArchive::now($writeModelText, $filename, $dir);

        return true;
    }

    public static function mold(): string
    {
        $texto = file_get_contents(__DIR__ . '/Molds/HttpMold.mold');
        return $texto;
    }

    private static function SetType($type, $last = true)
    {
        $quote = '"';
        $comma = $last ? "," : "";
        $comma = $comma . PHP_EOL;
        $typeData = array(
            'tinyint' => '1' . $comma,
            'smallint' => '1' . $comma,
            'mediumint' => '1' . $comma,
            'int' => '1' . $comma,
            'integer' => '1' . $comma,
            'bigint' => '1' . $comma,
            'float' => '1.1' . $comma,
            'double' => '1.1' . $comma,
            'decimal' => '1.1' . $comma,
            'char' => $quote . 'A' . $quote . $comma,
            'varchar' => $quote . 'Loren Epsun' . $quote . $comma,
            'text' => $quote . 'Loren Epsun' . $quote . $comma,
            'tinytext' => $quote . 'Loren Epsun' . $quote . $comma,
            'mediumtext' => $quote . 'Loren Epsun' . $quote . $comma,
            'longtext' => $quote . 'Loren Epsun' . $quote . $comma,

            'date' => $quote . '2001-05-11' . $quote . $comma,
            'time' => $quote . '10:00:03' . $quote . $comma,
            'datetime' => $quote . '2001-05-11 10:00:03' . $quote . $comma,
            'timestamp' => $quote . '2001-05-11 10:00:03' . $quote . $comma,
            'year' => $quote . '2001' . $quote . $comma,

            'binary' => '0' . $comma,
            'varbinary' => $quote . '001100' . $quote . $comma,
            'blob' => $quote . 'file' . $quote . $comma,
            'tinyblob' => $quote . 'file' . $quote . $comma,
            'mediumblob' => $quote . 'file' . $quote . $comma,
            'longblob' => $quote . 'file' . $quote . $comma,

            'boolean' => 'true' . $comma,
            'bool' => 'true' . $comma,
            'enum' => $quote . 'value' . $quote . $comma,
            'set' => $quote . 'value' . $quote . $comma,
            'json' => $quote . '[]' . $quote . $comma,
        );

        return $typeData[$type] ?? "";

    }

    private static function setModel($table)
    {
        $names = explode('_', $table);
        $model = '';
        foreach ($names as $name) {
            $model .= ucfirst($name);
        }
        return rtrim($model, 's');
    }

    private static function setColumns(array $columns)
    {
        if (empty($columns)) {
            return false;
        }
        $dataColumns = '';
        $quote = '"';
        $countColumns = (count($columns) - 1);
        foreach ($columns as $i => $column) {
            if ($i < ($countColumns)) {
                $dataColumns .= '    ' . $quote . $column->name . $quote . ":" . self::SetType($column->type);
            } else {
                $dataColumns .= '    ' . $quote . $column->name . $quote . ":" . self::SetType($column->type, false);
            }

        }
        return $dataColumns;

    }




}
