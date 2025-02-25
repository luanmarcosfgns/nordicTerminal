<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use App\TollBox\Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$token) {
            abort(401, 'Unauthorized');
        }
        return response()->json(['data' => ['token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60]
        ]);
    }

    public function me(): JsonResponse
    {
        $credentials = auth()->user();

        if (empty($credentials)) {
            abort(403, 'Unautorized');
        }
        return response()->json($credentials);
    }

    public function validated($type,$request){
        if($type=="store"){
            $request->validate(
                [
                    'nome'=>['required','max:255','string'],
                    'email'=>['email','required','max:255','string','unique:users,email'],
                    'password'=>['nullable','max:255','string'],
                ]
            );
        }else{
            $request->validate([
                'nome'=>['nullable','max:255','string'],
                'email'=>['email','nullable','max:255','string'],
                'password'=>['nullable','max:255','string'],
                'ativo'=>['nullable','boolean'],
                'deleted_at'=>['nullable'],
            ]);
        }
        return $request->only(["nome","email","password","ativo","deleted_at"]);
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
        $users = User::search($search)
            ->paginate(1000);

        return response()->json($users);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);
        $validated['ativo'] = 1;
        $user = User::create($validated);
        $token = Auth::login($user);

        return response()->json(['success'=>true,'token'=>$token]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $user = User::find($id);

        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $user = User::find($id);
        $validated = $this->validated("update",$request);
        if (empty($validated['password'])){
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = User::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->limit(100)->where('nome', 'like', '%' . $request->search . '%')
                ->get();
            return response()->json($rows);
        }


        if (!empty($request->id)) {
            $rows = User::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = User::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->limit(25)
            ->get();
        return response()->json($rows);
    }

}
