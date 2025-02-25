<?php

namespace App\Util;

class StringManipulate
{

    public static function overFlowRow(string $texto,int $length=91)
    {
        // Divide o texto em linhas
        $linhas = explode("\n", $texto);

        // Percorre cada linha
        foreach ($linhas as $linha) {
            // Enquanto a linha tiver mais de 91 caracteres, adiciona quebras de linha
            while (strlen($linha) > $length) {
                // Encontra a posição ideal para quebrar a linha (91º caractere ou antes)
                $posicaoQuebra = strrpos(substr($linha, 0, $length+1), ' ');

                // Se não houver espaço para quebrar, quebra no 91º caractere
                if ($posicaoQuebra === false) {
                    $posicaoQuebra = $length;
                }

                // Adiciona a quebra de linha
                $linha = substr($linha, 0, $posicaoQuebra) . "\n" . substr($linha, $posicaoQuebra + 1);
            }
        }

        // Junta as linhas novamente em um único texto
        $output =  implode("\n", $linhas);
        $output = rtrim($output, "\r\n");
        return $output;
    }


}
