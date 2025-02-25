<?php

namespace App\Http\Controllers;

use App\Models\Comand;
use App\Models\Connection;
use App\TollBox\Helper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use phpseclib3\Net\SSH2;


class ComandController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [

                    'nome' => ['required', 'max:255', 'string'],
                    'comand' => ['required', 'max:4294967295', 'string'],
                ]
            );
        } else {
            $request->validate([
                'nome' => ['required', 'max:255', 'string'],
                'comand' => ['required', 'max:4294967295', 'string'],
            ]);
        }
        return $request->only(["nome", "comand", 'connection_id']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        $search = $request->get("search", "");
        if ($search == null) {
            $search = "";
        }
        $comands = Comand::search($search)
            ->selectRaw(
                'comands.id,
                comands.comand,
                 comands.nome'
            )
            ->join('connections', 'connections.id', '=', 'comands.connection_id')
            ->paginate(1000);

        return response()->json($comands);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store", $request);
        $comand = Comand::create($validated);

        return response()->json($comand);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $comand = Comand::find($id);

        return response()->json($comand);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse
    {

        $comand = Comand::find($id);
        $validated = $this->validated("update", $request);

        $comand->update($validated);

        return response()->json($comand);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $comand = Comand::find($id);
        $comand->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Comand::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->limit(100)->where('nome', 'like', '%' . $request->pesquisa . '%')
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Comand::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Comand::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->limit(25)
            ->get();
        return response()->json($rows);
    }

    public function execute($connection_id)
    {
        try {

            $comand = request()->comand;
            $connection = Connection::find($connection_id);
            $host = $connection->host;
            $port = $connection->port;
            $usuario = $connection->username;
            $senha = Crypt::decryptString($connection->password);


            // Criar conexão SSH
            $ssh = new SSH2($host, $port, 20);


            // Autenticar com usuário e senha
            if (!$ssh->login($usuario, $senha)) {
                return response()->json(['success' => false, 'output' => 'Autenticação falhou.']);

            }
            $saida = $ssh->exec($comand);

            return response()->json(['success' => true, 'output' => $saida]);
        }catch (\Exception $exception){
            return response()->json(['success' => false, 'output' => $exception->getMessage()]);
        }

    }

}
