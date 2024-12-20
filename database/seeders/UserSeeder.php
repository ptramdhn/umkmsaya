<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        User::factory()->withRole('admin')->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Buat 2 pengguna dengan role customer dan seller
        User::factory()->withRoles(['customer', 'seller'])->count(2)->create();

        // Buat 2 pengguna dengan role customer saja
        User::factory()->withRole('customer')->count(2)->create();
    }
}
