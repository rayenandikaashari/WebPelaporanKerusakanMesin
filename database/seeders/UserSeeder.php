<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Operator',
                'email' => 'operator@gmail.com',
                'password' => 'Operator123!',
                'role' => 'operator',
            ],
            [
                'name' => 'Teknisi',
                'email' => 'teknisi@gmail.com',
                'password' => 'Teknisi123!',
                'role' => 'teknisi',
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@gmail.com',
                'password' => 'Supervisor123!',
                'role' => 'supervisor',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
