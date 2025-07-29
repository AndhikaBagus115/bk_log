<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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
                'nama' => 'MTS Al Huda',
                'username' => 'alhuda',
                'password' => Hash::make('alhuda123'),
                'api_token' => Str::random(60),
            ],
            [
                'nama' => 'MTS Nurul Falah',
                'username' => 'nurulfalah',
                'password' => Hash::make('falah123'),
                'api_token' => Str::random(60),
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }

}
