<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
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

        // Cek kredensial seperti sebelumnya
        if ($client && Hash::check($credentials['password'], $client->password)) {

            // GANTI BAGIAN INI
            // session(['client_id' => $client->id]); // <-- Hapus baris ini

            // GUNAKAN INI untuk membuat sesi login yang dikenali Laravel
            Auth::guard('client')->login($client);

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Arahkan ke tujuan yang diingat sebelumnya
            return redirect()->intended(route('bk.index'))->with('success', 'Login berhasil');
        }

        return back()
            ->withErrors(['login' => 'Username atau password salah'])
            ->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        // GANTI BAGIAN INI
        // session()->forget('client_id'); // <-- Hapus baris ini

        // GUNAKAN INI untuk logout secara resmi
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout');
    }
}
