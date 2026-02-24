<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'username' => 'admin@gmail.com',
            'user_type' => 'admin',
            'short_name' => 'AD',
            'password' => Hash::make('Admin'),
            'status' => 'active'
        ]);
    }
}
