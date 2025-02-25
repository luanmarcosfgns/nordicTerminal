<?php

namespace App\Console\Commands\CrudGenerator\Template;

use App\Console\Commands\CrudGenerator\Util\WriteArchive;
use App\Console\Commands\CrudGenerator\Util\WriteModelText;
use Illuminate\Support\Facades\DB;

class ControllerMold
{

    public static function buildController(string $table)
    {
        $beLongTo = "";
        $database = getenv('DB_DATABASE');

        $columnsList = self::getColumns($table, $database);
        if (empty($columnsList)) {
            echo 'The table not exists' . PHP_EOL;
            return false;
        }
        $validatedStoreUpdate = self::validatedStoreUpdate($columnsList);
        $join = self::buildJoin($columnsList, $table);
        $columns = self::setColumns($columnsList);
        $selectColumns = self::selectColumns($columnsList, $table);
        if (!$columns) {
            echo 'The table not exists' . PHP_EOL;
            return false;
        }

        $useModel = self::useModel($table);

        $model = self::setModel($table);

        $tableSingular = self::setTableSingular($table);
        $writeModelText = WriteModelText::render(self::mold(), [
            ':useModel' => $useModel,
            ':validatedStoreUpdate' => $validatedStoreUpdate,
            ':tableSingular' => $tableSingular,
            ':table' => $table,
            ':model' => $model,
            ':columns' => $columns,
            ':selectColumns' => $selectColumns,
            ':join' => $join
        ]);
        $dir = app_path('/Http/Controllers');
        $filename = $model . 'Controller.php';
        WriteArchive::now($writeModelText, $filename, $dir);

        return true;
    }

    public static function mold(): string
    {
        $texto = file_get_contents(__DIR__ . '/Molds/ControllerMold.mold');
        return $texto;

    }

    private static function validatedStoreUpdate(array $columns)
    {
        $scriptValidate = 'if($type=="store"){
        $request->validate(
        :validated
        );
    }else{
        $request->validate(:validated);
    }';
        $validated = "[" . PHP_EOL;

        foreach ($columns as $column) {

            $quote = "'";
            $comma = ",";
            $tab = "    ";
            $columnValidated = $tab . $tab . $tab . $quote . $column->name . $quote . "=>[";

            if($column->name!=='id') {
                if (strpos($column->name, 'email') !== false) {
                    $columnValidated .= $quote . 'email' . $quote . $comma;
                }
                if ($column->is_nullable == "YES") {
                    $columnValidated .= $quote . 'nullable' . $quote . $comma;
                } else {
                    $columnValidated .= $quote . 'required' . $quote . $comma;
                }
                if ($column->length > 0 || !empty($column->length)) {
                    $columnValidated .= $quote . "max:" . $column->length . $quote . $comma;
                }


                $columnValidated .= self::SetType($column->type);


                $validated .= $columnValidated . '],' . PHP_EOL;
            }
        }

        $validated .= $tab . $tab . "]";

        $scriptValidate = str_replace(':validated', $validated, $scriptValidate);
        $scriptValidate = str_replace(',]', ']', $scriptValidate);
        return $scriptValidate;

    }

    private static function useModel(string $table)
    {
        $use = "use App\Models\:model;";
        $names = explode('_', $table);
        $model = '';
        foreach ($names as $name) {
            $model .= ucfirst($name);
        }

        $use = str_replace(':model', rtrim($model, 's'), $use);
        return $use;
    }


    private static function setTableSingular($table)
    {
        return rtrim($table, 's');
    }

    private static function SetType($type)
    {
        $quote = "'";
        $comma = ",";
        $typeData = array(
            'tinyint' => $quote . 'boolean' . $quote . $comma,
            'smallint' => '',
            'mediumint' => '',
            'int' => '',
            'integer' => '',
            'bigint' => '',
            'float' => $quote . 'numeric' . $quote . $comma,
            'double' => $quote . 'numeric' . $quote . $comma,
            'decimal' => $quote . 'numeric' . $quote . $comma,

            'char' => $quote . 'string' . $quote . $comma,
            'varchar' => $quote . 'string' . $quote . $comma,
            'text' => $quote . 'string' . $quote . $comma,
            'tinytext' => $quote . 'string' . $quote . $comma,
            'mediumtext' => $quote . 'string' . $quote . $comma,
            'longtext' => $quote . 'string' . $quote . $comma,

            'date' => $quote . 'date' . $quote . $comma,
            'time' => '',
            'datetime' => $quote . 'date' . $quote . $comma,
            'timestamp' => '',
            'year' => '',

            'binary' => $quote . 'boolean' . $quote . $comma,
            'varbinary' => $quote . 'boolean' . $quote . $comma,
            'blob' => $quote . 'string' . $quote . $comma,
            'tinyblob' => $quote . 'string' . $quote . $comma,
            'mediumblob' => $quote . 'string' . $quote . $comma,
            'longblob' => $quote . 'string' . $quote . $comma,

            'boolean' => $quote . 'boolean' . $quote . $comma,
            'bool' => $quote . 'boolean' . $quote . $comma,
            'enum' => '',
            'set' => '',
            'json' => $quote . 'json' . $quote . $comma,
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
        $listName = [];
        foreach ($columns as $column) {
            $listName[] = $column->name;
        }
        return json_encode($listName);

    }

    private static function selectColumns(array $columnsList, $table)
    {
        if (empty($columnsList)) {
            return false;
        }
        $listName = [];
        foreach ($columnsList as $column) {
            if(!in_array($column->type,['tinytext', 'text', 'mediumtext', 'longtext','blob','tinyblob','mediumblob','longblob'])){
                if (str_contains($column->type, 'float')) {
                    $listName[] = 'REPLACE(CAST(FORMAT('.$table.'.'.$column->name.',2) as CHAR),".",",") as "'.$column->name.'"';
                } elseif (in_array($column->type, ['double', 'decimal'])) {
                    $listName[] = 'REPLACE(CAST(FORMAT('.$table.'.'.$column->name.',2) as CHAR),".",",") as "'.$column->name.'"';
                } elseif (str_contains($column->name, '_id')) {
                    $listName[] = self::joinColumn($table,$column);
                } elseif ($column->type === 'date') {
                    $listName[] = ' DATE_FORMAT('.$table.'.'.$column->name.', "%d/%m/%Y") AS "'.$column->name.'"';
                } elseif ($column->type === 'datetime') {
                    $listName[] = 'DATE_FORMAT('.$table.'.'.$column->name.', "%d/%m/%Y %H:%i:%s") AS "'.$column->name.'"';
                } elseif ($column->type === 'bool' || $column->type === 'tinyint') {
                    $listName[] = 'IF('.$table.'.'.$column->name.'===true, "Sim","Não") AS "'.$column->name.'"';
                } else {
                    $listName[] = $table . '.' . $column->name;
                }
            }


        }
        return implode(',' . PHP_EOL, $listName);

    }

    private static function buildJoin(array $columns, string $table)
    {
        $join = '';

        foreach ($columns as $column) {
            if (str_contains($column->name, '_id')) {
                $tableJoin = explode('_id', $column->name)[0];
                $exists = self::existsTable($tableJoin);

                if (!$exists) {
                    $tableJoin = $tableJoin . 's';
                    $exists = self::existsTable($tableJoin);
                    if (!$exists) {
                        $tableJoin = false;
                    }
                }
                if ($tableJoin) {
                    $join .= "->join('$tableJoin','$tableJoin.id','=','$table.$column->name')";
                }
            }

        }
        return $join;
    }

    private static function joinColumn($table,mixed $column)
    {
        $rowSQL = '';
        $database = getenv('DB_DATABASE');

        if (str_contains($column->name, '_id')) {
            $tableJoin = explode('_id', $column->name)[0];
            $exists = self::existsTable($tableJoin);
            if ($exists) {
                $columns =  self::getColumns($tableJoin,$database);
            }else{
                $tableJoin = $tableJoin . 's';
                $exists = self::existsTable($tableJoin);
                if ($exists) {
                    $columns =  self::getColumns($tableJoin,$database);
                }
            }
            $rowSQL = 'concat('.$tableJoin.'.'.$columns[0]->name.',"-",'.$tableJoin.'.'.$columns[1]->name. ') as "'.$column->name.'"';

            return $rowSQL;

        }
        return $table.'.'.$column->name;
    }

    public static function existsTable($table): bool
    {
        $database = getenv('DB_DATABASE');

        $sql = "select true
from information_schema.COLUMNS
where TABLE_NAME = '$table'
    and TABLE_SCHEMA = '$database' limit 1";
        $restorno = DB::select($sql);
        return $restorno ? true : false;

    }

    private static function getColumns(string $table, bool|array|string $database): array
    {
        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length,IS_NULLABLE as is_nullable
from information_schema.COLUMNS
where TABLE_NAME = ':table'
    and TABLE_SCHEMA = ':database'
    and COLUMN_NAME not in ('created_at','updated_at')
    ";

        $sql = str_replace(':table', $table, $sql);
        $sql = str_replace(':database', $database, $sql);

        return DB::select($sql);
    }


}

