<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@maduramart.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'alamat' => 'Jl. Admin No. 1',
            'no_telepon' => '081234567890',
            'foto' => 'admin.jpg',
        ]);

        User::create([
            'name' => 'Owner',
            'email' => 'owner@maduramart.com',
            'password' => Hash::make('123'),
            'role' => 'owner',
            'alamat' => 'Jl. Owner No. 2',
            'no_telepon' => '081234567891',
            'foto' => 'owner.jpg',
        ]);

        User::create([
            'name' => 'Courier',
            'email' => 'courier@maduramart.com',
            'password' => Hash::make('123'),
            'role' => 'courier',
            'alamat' => 'Jl. Courier No. 3',
            'no_telepon' => '081234567892',
            'foto' => 'courier.jpg',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@maduramart.com',
            'password' => Hash::make('123'),
            'role' => 'customer',
            'alamat' => 'Jl. Customer No. 4',
            'no_telepon' => '081234567893',
            'foto' => 'customer.jpg',
        ]);
    }
}