<?php

namespace Workbench\Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use A17\Twill\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = 'test@test.test';
        $password = 'test@test.test';

        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'role' => 'SUPERADMIN',
            'published' => true,
        ]);

        $user->password = Hash::make($password);

        $user->save();
    }
}
