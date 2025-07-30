<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientWebController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $client = Client::where('username', $credentials['username'])->first();

        if ($client && Hash::check($credentials['password'], $client->password)) {
            session(['client_id' => $client->id]);
            return redirect('/bk')->with('success', 'Login berhasil');
        }

        return back()
            ->withErrors(['login' => 'Username atau password salah'])
            ->withInput($request->only('username'));
    }


    public function logout()
    {
        session()->forget('client_id');
        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
