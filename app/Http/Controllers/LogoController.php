<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class LogoController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $client = Auth::guard('client')->user();

        // Ensure $client is an instance of App\Models\Client
        if (!$client || !($client instanceof \App\Models\Client)) {
            return back()->with('error', 'Gagal, data client tidak ditemukan.');
        }

        // Hapus logo lama jika ada
        if ($client->logo) {
            Storage::delete($client->logo);
        }

        // --- PERUBAHAN UTAMA DI SINI ---

        // 1. Simpan file secara eksplisit ke disk 'public' di dalam folder 'images'.
        //    Ini akan mengembalikan path relatif seperti 'images/namafile.png'.
        $relativePath = $request->file('logo')->store('images', 'public');

        // 2. Buat path lengkap untuk disimpan di database agar konsisten dengan
        //    kode kita yang lain (seperti di Excel Export).
        $fullPath = 'public/' . $relativePath;

        // 3. Simpan path lengkap ke database.
        $client->logo = $fullPath;
        $client->save();

        return back()->with('success', 'Logo berhasil diungah!');
    }

    public function deleteLogo()
    {
        $clientId = Auth::guard('client')->id();
        $client = Client::find($clientId);

        if ($client && $client->logo) {
            Storage::disk('public')->delete(str_replace('public/', '', $client->logo));
            $client->logo = null;
            $client->save();
        }

        return redirect()->back()->with('success', 'Logo berhasil dihapus.');
    }
}
