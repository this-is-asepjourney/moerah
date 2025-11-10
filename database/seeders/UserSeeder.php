<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@fashionstore.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'email_verified_at' => now(),
        ]);

        // Create Customer User
        User::create([
            'name' => 'Customer User',
            'email' => 'customer@fashionstore.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567891',
            'address' => 'Jl. Customer No. 2',
            'email_verified_at' => now(),
        ]);

        // Create additional test customers
        User::factory(5)->create([
            'role' => 'customer'
        ]);
    }
}