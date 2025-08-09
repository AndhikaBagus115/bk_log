<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'nama' => 'MTS Contoh',
                'username' => 'mtscontoh',
                'password' => Hash::make('123456'),
                'api_token' => Str::random(60),
            ],
            [
                'nama' => 'MTS Suguja',
                'username' => 'sugujagurah',
                'password' => Hash::make('suguja2025'),
                'api_token' => Str::random(60),
            ],
            [
                'nama' => 'MTS Nurul Falah',
                'username' => 'nurulfalah',
                'password' => Hash::make('falah123'),
                'api_token' => Str::random(60),
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }
}
