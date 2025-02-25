<?php

namespace App\Console\Commands\CrudGenerator\Template;

use App\Console\Commands\CrudGenerator\Util\WriteArchive;
use App\Console\Commands\CrudGenerator\Util\WriteModelText;
use Illuminate\Support\Facades\DB;

class ViewMold
{
    public static function buildView($table)
    {
        $views = self::mold();
        $tableUppercase = self::setTableUppercase($table);
        $columns = self::setColumns($table);
        $tdColumns = self:: buidTdColumns($columns);
        $tableSingular = self::palavraSingular($table);
        $tableSingular = self::tableSingular($tableSingular);
        $tablePonto = self::tablePonto($table);
        $tableHifen = self::tableHifen($table);

        $index = WriteModelText::render($views['index'],
            [':tableUppercase' => $tableUppercase,
                ':tableSingular' => $tableSingular,
                ':tablePonto' => $tablePonto,
                ':tableHifen'=>$tableHifen,
                ':table' => $table,
                ':tdColumns' => $tdColumns,


            ]);

        $inputFom = self::buildInputForm($columns);

        $form = WriteModelText::render($views['form'],
            [':tableUppercase' => $tableUppercase,
                ':tablePonto' => $tablePonto,
                ':table' => $table,
                ':inputFom' => $inputFom
            ]);

        $dataFormSend = self::buildDataForm($columns);
        $dataFormEdit = self::buildEditForm($columns);

        $edit = WriteModelText::render($views['edit'],
            [':tableUppercase' => $tableUppercase,
                ':tablePonto' => $tablePonto,
                ':tableSingular' => $tableSingular,
                ':table' => $table,
                ':dataFormEdit' => $dataFormEdit,
                ':dataFormSend' => $dataFormSend]);


        $create = WriteModelText::render($views['create'],
            [':tableUppercase' => $tableUppercase,
                ':tablePonto' => $tablePonto,
                ':tableSingular' => $tableSingular,
                ':table' => $table,
                ':dataFormSend' => $dataFormSend]);

        $service = WriteModelText::render($views['service'],
            [':tableUppercase' => $tableUppercase,
                ':tablePonto' => $tablePonto,
                ':tableSingular' => $tableSingular,
                ':table' => $table,
                ':dataFormSend' => $dataFormSend]);

        $dir = self::diretorioFrontend();
        $dir = $dir . '/src/views/' . $table . '/';

        WriteArchive::now($index, 'Index' . $tableUppercase . '.vue', $dir);
        WriteArchive::now($form, 'Form' . $tableUppercase . '.vue', $dir);
        WriteArchive::now($edit, 'Edit' . $tableUppercase . '.vue', $dir);
        WriteArchive::now($create, 'Create' . $tableUppercase . '.vue', $dir);

        $dir = self::diretorioFrontend();
        $dir = $dir . '/src/services/';

        WriteArchive::now($service, $tablePonto . '.service.js', $dir);
        return true;
    }

    public static function mold()
    {
        $quote = "'";

        $data['index'] = file_get_contents(__DIR__ . '/Molds/IndexMold.vue');
        $data['create'] = file_get_contents(__DIR__ . '/Molds/CreateMold.vue');
        $data['form'] = file_get_contents(__DIR__ . '/Molds/FormMold.vue');
        $data['edit'] = file_get_contents(__DIR__ . '/Molds/EditMold.vue');
        $data['service'] = file_get_contents(__DIR__ . '/Molds/mold.service.js');

        return $data;
    }


    private static function setColumns($table)
    {
        $database = getenv('DB_DATABASE');

        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length,IS_NULLABLE as is_nullable,COLUMN_TYPE AS type_column
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
            die();
        }
        return $columns;
    }

    private static function buidTdColumns(array $columns): string
    {

        $col1 = '';
        $numberColumns = count($columns);
        if ($numberColumns <= 4) {
            foreach ($columns as $i => $column) {
                if (!in_array($column->type, ['tinytext', 'text', 'mediumtext', 'longtext','blob','tinyblob','mediumblob','longblob'])) {
                    $label = self::improveTextLabel($column);
                    $col1 .= '                 <div class="col-12"> <strong>' . $label . ': </strong>{{row.' . $column->name . '}}</div>' . PHP_EOL;

                }


            }
            $html = '
                <td>:col1</td>';
            $html = str_replace(':col1', $col1, $html);
            return $html;
        }
        $col2 = '';
        foreach ($columns as $i => $column) {
            if (!in_array($column->type, ['tinytext', 'text', 'mediumtext', 'longtext','blob','tinyblob','mediumblob','longblob'])) {
                if ($i <= 4) {
                    $label = self::improveTextLabel($column);
                    $col1 .= '                 <div class="col-12"> <strong>' . $label . ': </strong>{{row.' . $column->name . '}}</div>' . PHP_EOL;

                } else {
                    $label = self::improveTextLabel($column);
                    $col2 .= '                 <div class="col-12"> <strong>' . $label . ': </strong>{{row.' . $column->name . '}}</div>' . PHP_EOL;

                }
            }


        }
        $html = '
                <td>:col1</td>
                <td>:col2</td>';
        $html = str_replace(':col1', $col1, $html);
        $html = str_replace(':col2', $col2, $html);
        return $html;

    }

    private static function buidThColumns(?array $columns): string
    {
        $html = '';
        foreach ($columns as $i => $column) {
            if ($i < 8) {
                $html .= '                    <th>' . ucfirst($column->name) . '</th>' . PHP_EOL;
            }

        }
        return $html;
    }

    private static function buildInputForm(array $columns): string
    {
        $html = '';

        foreach ($columns as $column) {
            $label = self::improveTextLabel($column);


            if (in_array($column->name, ['cpf', 'CPF'])) {
                $html .= '<input-form class-list="col-12"  type="mask" mask="000.000.000-00" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif (in_array($column->name, ['cnpj', 'CNPJ'])) {
                $html .= '<input-form class-list="col-12"  type="mask" mask="00.000.000/0000-00" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif (in_array($column->name, ['cep', 'CEP'])) {
                $html .= '<input-form class-list="col-12"  type="mask" mask="00000-000" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif (in_array($column->name, ['telefone', 'celular','movel'])) {
                $html .= '<input-form class-list="col-12"  type="mask" mask="(00) 0 0000-0000" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif (in_array($column->name, ['ip', 'ip_address'])) {
                $html .= '<input-form class-list="col-12"  type="mask" mask="(00) 0 0000-0000" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif ($column->type == 'varchar') {
                $html .= '<input-form class-list="col-12"  type="string" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif ($column->type == 'longtext') {
                $html .= '<input-form class-list="col-12"  type="text" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif (strpos($column->name, '_id')) {
                $table = str_replace('_id', 's', $column->name);
                $html .= '  <input-form placeholder="Selecione ' . $label . '" class-list="col-12" type="select2" url="/api/' . $table . '/list" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } elseif ($column->type == 'enum') {
                $enum = str_replace('enum(', '', $column->type_column);
                $enum = str_replace(')', '', $enum);

                $columnTypes = explode(',', $enum);

                $data = '[';
                foreach ($columnTypes as $columnType) {

                    $data .= "{id:" . $columnType . ',message:' . $columnType . ",},";
                }
                $data .= ']';

                $html .= '  <input-form placeholder="Selecione ' . $label . '" class-list="col-12" type="select" :items="' . $data . '" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            } else {
                $html .= '<input-form class-list="col-12"  type="' . $column->type . '" label="' . $label . '" value="" name="' . $column->name . '"/>' . PHP_EOL;
            }

        }
        return $html;
    }

    private static function buildDataForm(?array $columns): string
    {
        $script = '';
        foreach ($columns as $column) {
            if(!in_array($column->name, ['cpf', 'CPF','cnpj', 'CNPJ','cep', 'CEP','telefone', 'celular','movel','ip', 'ip_address'])){
                $script .= $column->name . ": document.getElementById('" . $column->name . "').value," . PHP_EOL;
            }else{
                $script .= $column->name . ": String(document.getElementById('" . $column->name . "').value).replace(/[^a-zA-Z0-9]/g, '')," . PHP_EOL;
            }

        }
        return $script;
    }

    private static function buildEditForm(?array $columns): string
    {
        $script = '';
        foreach ($columns as $column) {
            $script .= "document.getElementById('" . $column->name . "').value = response.data." . $column->name . ';' . PHP_EOL;

        }
        return $script;
    }

    private static function diretorioFrontend(): string
    {
        $dirArray = explode('/', base_path());
        $numberArray = count($dirArray);
        $dirArray[($numberArray - 1)] = 'frontend';
        return implode('/', $dirArray);
    }

    private static function setTableUppercase($table): string
    {
        $listModelName = explode('_', $table);
        $numberListName = count($listModelName);
        $modelName = "";
        foreach ($listModelName as $i => $word) {
            $modelName .= ucfirst($word);

        }
        return $modelName;

    }

    private static function palavraSingular($table)
    {
        $tableSingular = $table;
        if (substr($table, -1) === "s") {
            // Remove o último caractere
            $tableSingular = substr($tableSingular, 0, -1);
        }
        return $tableSingular;
    }

    private static function improveTextLabel($column)
    {
        $label = str_replace('_id', '', $column->name);
        $label = str_replace('_', ' ', $label);
        $label = str_replace('cnpj', 'CNPJ', $label);
        $label = str_replace('cpf', 'CPF', $label);
        $label = str_replace('rg', 'RG', $label);
        $label = str_replace('ie', 'IE', $label);
        $label = str_replace('endereco', 'Endereço', $label);
        $label = str_replace('numero', 'Número', $label);

        $labelArray = explode(' ', $label);
        $label = '';
        foreach ($labelArray as $item) {
            $item = self::findDictionary($item);
            $label .= ucfirst($item) . ' ';
        }

        $labelArray = explode('_', $label);
        $label = '';
        foreach ($labelArray as $item) {
            $item = self::findDictionary($item);
            $label .= ucfirst($item) . ' ';
        }
        return $label;
    }

    private static function findDictionary($label)
    {
        $data['dictionary'] =
        $texto = file_get_contents(__DIR__ . '/Molds/dictionary.txt');
        $linhas = explode(PHP_EOL, $texto);
        foreach ($linhas as $linha) {
            $isEqual = self::isEqual($label, $linha);
            if ($isEqual) {
                return $linha;
            }

        }
        return $label;
    }


    private static function isEqual($subject1, $subject2)
    {
        $row = $subject2;
        // Define um array associativo de substituições
        $substituicoes = array(
            '/[àáâãäå]/u' => 'a',
            '/[èéêë]/u' => 'e',
            '/[ìíîï]/u' => 'i',
            '/[òóôõö]/u' => 'o',
            '/[ùúûü]/u' => 'u',
            '/ñ/u' => 'n',
            '/ç/u' => 'c'
        );

        // Normaliza as strings para minúsculas
        $subject1 = mb_strtolower($subject1, 'UTF-8');
        $subject2 = mb_strtolower($subject2, 'UTF-8');

        // Aplica as substituições
        $subject1 = preg_replace(array_keys($substituicoes), array_values($substituicoes), $subject1);
        $subject2 = preg_replace(array_keys($substituicoes), array_values($substituicoes), $subject2);

        // Remove caracteres não alfanuméricos
        $subject1 = preg_replace('/[^a-z0-9]/', '', $subject1);
        $subject2 = preg_replace('/[^a-z0-9]/', '', $subject2);


        // Compara as strings
        return strcasecmp($subject1, $subject2) === 0;
    }

    private static function tableSingular(mixed $tableSingular)
    {
        $rows = explode('_',$tableSingular);
        $content = '';
        foreach ($rows as $row) {
            $content .= ucfirst($row);
        }
        return $content;
    }

    private static function tablePonto($table)
    {
        return str_replace('_','.',$table);
    }

    private static function tableHifen($table)
    {
        return str_replace('_','-',$table);
    }


}
