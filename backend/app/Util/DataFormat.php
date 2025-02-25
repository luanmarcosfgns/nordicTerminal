<?php

namespace App\Util;

class DataFormat
{
    public static function unmask($value)
    {
        return preg_replace('/[^0-9]/', '', $value);

    }

    public static function autoFloat(string $input)
    {

        // trata esse formato  de numero 1000,000,000.00
        if (substr_count($input, '.') == 1 && substr_count($input, ',') > 1 || strripos($input, ',') < strripos($input, '.')) {

            $input = str_replace(',', '', $input);

            // trata esse formato  de numero 1000.000.000,00
        } else if (substr_count($input, ',') == 1 && substr_count($input, '.') > 1 || strripos($input, ',') > strripos($input, '.')) {
            $input = str_replace('.', '', $input);
            $input = str_replace(',', '.', $input);

            // trata esse formato  de numero 1000 000 000.00
        } else if (substr_count($input, ' ') > 1 && substr_count($input, '.') == 1 || strripos($input, ' ') < strripos($input, '.')) {
            $input = str_replace(' ', '', $input);

            // trata esse formato  de numero 1000 000 000.00
        } else if (substr_count($input, ' ') > 1 && substr_count($input, ',') == 1 || strripos($input, ' ') > strripos($input, '.')) {
            $input = str_replace(' ', '', $input);
            $input = str_replace(',', '.', $input);

            // trata esse formato  de numero 100,00
        } else if (substr_count($input, ',') == 1) {
            $input = str_replace(',', '.', $input);

            // trata esse formato  de numero 100.00
        } else if (substr_count($input, '.') == 1) {
            $input = $input;

            //retorna erro se não se encaixar em nenhuma das categorias
        } else if (is_numeric($input)) {
            return $input;
        } else {
            abort(500, "Houve um problema  na hora de savar o valor: " . $input);
        }

        return $input;

    }

    /**
     * @param $texto
     * @return array|string|string[]|null
     */
    public static function retiraAcentos($texto)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/", "/(°)/"), explode(" ", "a A e E i I o O u U n N c C o"), $texto);
    }

    public static function format($number, $dec = 4)
    {
        return number_format((float)$number, $dec, ".", "");
    }


    /**
     * deixa só os numeros do CNPJ
     * @param $cnpj
     * @return String
     */
    public static function retirarMascaraCNPJ($cnpj): string
    {
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        $cnpj = str_replace(" ", "", $cnpj);
        return $cnpj;
    }

    /**
     * deixa só os numeros do CPF
     * @param $cnpj
     * @return String
     */
    public static function retirarMascaraCPF($cnpj): string
    {
        $cnpj = str_replace(".", "", $cnpj);
        $cnpj = str_replace("/", "", $cnpj);
        $cnpj = str_replace("-", "", $cnpj);
        $cnpj = str_replace(" ", "", $cnpj);
        return $cnpj;
    }


    /**
     * deixa só os numeros do CEP
     * @param $cnpj
     * @return String
     */
    public static function retirarMascaraCEP($cep): string
    {

        $cep = str_replace("-", "", $cep);

        return $cep;
    }

    public static function objectFromXML($object)
    {

        return $result = ArrayToXml::convert(json_decode(json_encode($object), true));

    }

    public static function retirarMascaraTelefone(string $telefone)
    {
        $telefone = str_replace("-", "", $telefone);
        $telefone = str_replace(")", "", $telefone);
        $telefone = str_replace("(", "", $telefone);
        $telefone = str_replace(" ", "", $telefone);
        $telefone = str_replace("+", "", $telefone);
        return $telefone;
    }

    public static function retirarMascaraIE($ie): string
    {
        $ie = str_replace(".", "", $ie);
        $ie = str_replace("/", "", $ie);
        $ie = str_replace("-", "", $ie);
        $ie = str_replace(" ", "", $ie);
        return $ie;
    }

    public static function primeiraLetraMaiusculaPalavra($str)
    {
        return ucwords(strtolower($str));
    }
}
