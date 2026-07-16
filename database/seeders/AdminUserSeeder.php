<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('KIOSKHELD_ADMIN_EMAIL', 'admin@kioskheld.test');
        $name = env('KIOSKHELD_ADMIN_NAME', 'Kioskheld Admin');
        $password = env('KIOSKHELD_ADMIN_PASSWORD', 'password');

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'remember_token' => Str::random(10),
                'platform_role' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
