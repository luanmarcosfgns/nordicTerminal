<?php
namespace App\Http\Controllers;

use App\Services\SSHService;
use App\TollBox\Helper;
use App\Util\StringManipulate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use phpseclib3\Net\SSH2;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Connection;
use Illuminate\Routing\Controller;
use  \Illuminate\Support\Facades\Queue;

class SSHController extends Controller
{
    public function execute(Request $request, $connection_id)
    {
        try {

            $command = $request->command;

            $terminal_id = $request->terminal_id;

            $connection = Connection::find($connection_id);

            if (!$connection) {
                abort(500, 'Conexão não encontrada.');
            }

            $host = $connection->host;
            $port = $connection->port;
            $usuario = $connection->username;
            $senha = Crypt::decryptString($connection->password);

            $executionId = Str::uuid();

            Cache::put("ssh_execution_{$executionId}", ['status' => 'running', 'output' => null, 'requires_confirmation' => false], 600);

            Queue::push(function () use ($host, $port, $usuario, $senha, $command, $executionId, $terminal_id) {
                try {
                    $ssh = new SSHService($host, $port,$usuario, $senha,$terminal_id);

                    $output = $ssh->execute($command);

                    $output = StringManipulate::overFlowRow($output);
                    Log::info($output);

                    $payload = ['status' => 'completed', 'output' => $output];

                    $requires_confirmation = strpos($output, 'confirm') !== false;

                    if ($requires_confirmation) {
                        $payload = ['status' => 'waiting_confirmation', 'output' => $output];
                    }

                    Cache::put("ssh_execution_{$executionId}", $payload, 600);

                } catch (\Exception $exception) {
                    Cache::put("ssh_execution_{$executionId}", ['status' => 'failed', 'output' => $exception->getMessage(), 'requires_confirmation' => false], 600);
                }
            });

        } catch (\Exception $exception) {
            $payload = ['status' => 'failed', 'output' => $exception->getMessage()];
            Cache::put("ssh_execution_{$executionId}", $payload, 600);
        }

        return response()->json(['success' => true, 'execution_id' => $executionId]);
    }

    public function checkStatus($executionId)
    {
        $executionData = Cache::get("ssh_execution_{$executionId}", null);


        if (!$executionData) {
            return response()->json(['output' => 'Execução não encontrada', 'status' => 'failed']);
        }

        return response()->json([ 'status' => $executionData['status'], 'output' => $executionData['output']]);
    }

}
