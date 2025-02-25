<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;


class ConnectionController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'nome' => ['required', 'max:255', 'string'],
                    'host' => ['required', 'max:255', 'string'],
                    'port' => ['required'],
                    'username' => ['required', 'max:255', 'string'],
                    'password' => ['nullable', 'max:255', 'string'],
                    'public_key' => ['nullable', 'max:255', 'string'],
                    'private_key' => ['nullable', 'max:255', 'string'],
                ]
            );
        } else {
            $request->validate([
                'nome' => ['required', 'max:255', 'string'],
                'host' => ['required', 'max:255', 'string'],
                'port' => ['required'],
                'username' => ['required', 'max:255', 'string'],
                'password' => ['nullable', 'max:255', 'string'],
                'public_key' => ['nullable', 'max:255', 'string'],
                'private_key' => ['nullable', 'max:255', 'string'],
            ]);
        }
        return $request->only(["id", "nome", "user_id", "host", "port", "username", "password", "public_key", "private_key", "passphrase"]);
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
        $connections = Connection::search($search)
            ->selectRaw(
                'connections.id,
                connections.nome,
                connections.host,
                connections.port,
                connections.username'
            )
            ->join('users', 'users.id', '=', 'connections.user_id')
            ->paginate(1000);

        return response()->json($connections);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store", $request);
        $validated['user_id'] = $request->user()->id;
        $validated['password'] = Crypt::encryptString($validated['password']);

        $connection = Connection::create($validated);

        return response()->json($connection);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $connection = Connection::select(
            'connections.nome',
            'connections.username',
            'connections.user_id',
            'connections.port',
            'connections.host',
            'connections.id',
            'connections.created_at',
            'connections.updated_at'
        )
            ->where('connections.id', $id)
            ->where('connections.user_id', $request->user()->id)
            ->first();

        return response()->json($connection);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse
    {

        $connection = Connection::find($id);
        if ($connection->user_id != $request->user()->id) {
            return response()->json(['success' => false], 422);
        }
        $validated = $this->validated("update", $request);
        if (!empty($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        }

        $connection->update($validated);

        return response()->json($connection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $connection = Connection::find($id);
        if ($connection->user_id != $request->user()->id) {
            return response()->json(['success' => false], 422);
        }
        $connection->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Connection::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->limit(100)->where('nome', 'like', '%' . $request->pesquisa . '%')
                ->where('connections.user_id', $request->user()->id)
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Connection::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Connection::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->limit(25)
            ->get();
        return response()->json($rows);
    }

}
