<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $client = Client::where('username', $request->username)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json(['message' => 'Login gagal'], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'token' => $client->api_token,
                'client_id' => $client->id
            ]
        ]);
    }
}
