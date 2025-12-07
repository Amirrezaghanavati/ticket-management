<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin1',
            'email' => 'admin1@ticket.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN1->value,
        ]);
        DB::table('users')->insert([
            'name' => 'admin2',
            'email' => 'admin2@ticket.com',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN2->value,
        ]);
    }
}
