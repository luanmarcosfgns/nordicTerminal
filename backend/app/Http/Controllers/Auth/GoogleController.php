<?php
namespace App\Http\Controllers\Auth;

use App\TollBox\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class GoogleController extends Controller
{
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct()
    {

        $this->clientId = env('GOOGLE_CLIENT_ID');
        $this->clientSecret = env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = env('GOOGLE_REDIRECT_URI');
    }

    // Retorna a URL de autenticação do Google
    public function getAuthUrl()
    {
        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUri,
                'response_type' => 'code',
                'scope' => 'openid email profile',
                'access_type' => 'offline',
            ]);

        return response()->json(['url' => $url]);
    }

    // Manipula o retorno do Google com o código
    public function handleCallback(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            return response()->json(['error' => 'Authorization code not provided'], 400);
        }

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
        ]);

        $token = $tokenResponse->json();
        if (!isset($token['access_token'])) {
            return response()->json(['error' => 'Token exchange failed'], 400);
        }

        $userInfoResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token['access_token'],
        ])->get('https://www.googleapis.com/oauth2/v3/userinfo');

        $userInfo = $userInfoResponse->json();

        // Procure o usuário no banco de dados ou crie um novo
        $user = User::updateOrCreate(
            ['email' => $userInfo['email']],
            [
                'nome' => $userInfo['name'],
                'google_id' => $userInfo['sub'],
                'avatar' => $userInfo['picture'],
                'password' => '@@335563ewdf4fhgjylm__',
                'ativo' =>1,
            ]
        );

        // Gere um token JWT ou use o sistema padrão do Laravel para autenticação
        $token = Auth::login($user);

        return response()->json(['data' => ['token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60]
        ]);
    }
}
