<?php

namespace App\Services;

use App\TollBox\Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use phpseclib3\Common\Functions\Strings;
use phpseclib3\Crypt\Blowfish;
use phpseclib3\Crypt\ChaCha20;
use phpseclib3\Crypt\Common\AsymmetricKey;
use phpseclib3\Crypt\Common\PrivateKey;
use phpseclib3\Crypt\Common\PublicKey;
use phpseclib3\Crypt\Common\SymmetricKey;
use phpseclib3\Crypt\DH;
use phpseclib3\Crypt\DSA;
use phpseclib3\Crypt\EC;
use phpseclib3\Crypt\Hash;
use phpseclib3\Crypt\Random;
use phpseclib3\Crypt\RC4;
use phpseclib3\Crypt\Rijndael;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\TripleDES;
use phpseclib3\Crypt\Twofish;
use phpseclib3\Exception\ConnectionClosedException;
use phpseclib3\Exception\InsufficientSetupException;
use phpseclib3\Exception\InvalidPacketLengthException;
use phpseclib3\Exception\NoSupportedAlgorithmsException;
use phpseclib3\Exception\TimeoutException;
use phpseclib3\Exception\UnableToConnectException;
use phpseclib3\Exception\UnsupportedAlgorithmException;
use phpseclib3\Exception\UnsupportedCurveException;
use phpseclib3\Math\BigInteger;
use phpseclib3\Net\SSH2;
use phpseclib3\System\SSH\Agent;

class SSHService
{
    private $command = "ssh_client -u :usuario -h :host -s \":password\" -p :port -i \":name\" -c \":command\"";
    public function __construct($host, $port,$username, $password,$name="")
    {
        $this->command = str_replace(':host', $host, $this->command);
        $this->command = str_replace(':port', $port, $this->command);
        $this->command = str_replace(':usuario', $username, $this->command);
        $this->command = str_replace(':password', $password, $this->command);
        $this->command = str_replace(':name', $name, $this->command);    }

    public function execute(string $commandTerminal)
    {
        Log::info($commandTerminal);
        try {
            $this->command = str_replace(':command', $commandTerminal, $this->command);
            $basePath = base_path("libs/");
            if (!file_exists($basePath.'/ssh_client')) {
                return "SSH_COMPILER_NOT_FOUND";
            }
            $commandFull = $basePath.$this->command;
            Log::info($basePath);

            // Executa o comando e captura a saída e o código de retorno
            exec($commandFull, $output, $return_var);

            if ($return_var !== 0) {
                return "Erro ao executar comando. Código de retorno: $return_var";
            }

            return implode(PHP_EOL,$output);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

}
