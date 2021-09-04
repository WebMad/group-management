<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'name' => 'Староста',
            'email' => env('APP_ADMIN_EMAIL'),
            'password' => Hash::make(env('APP_ADMIN_PASS'))
        ]);
    }
}
