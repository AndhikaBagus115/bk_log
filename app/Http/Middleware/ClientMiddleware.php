<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Client;

class ClientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        // Format token: Bearer xxxxxx
        $token = str_replace('Bearer ', '', $token);
        $client = Client::where('api_token', $token)->first();

        if (!$client) {
            return response()->json(['message' => 'Token tidak valid'], 401);
        }

        // Simpan client untuk digunakan di controller
        $request->merge(['client_id' => $client->id]);

        return $next($request);
    }
}
