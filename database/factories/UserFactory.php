<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone_number' => $this->faker->phoneNumber,
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function withRole(string $role)
    {
        return $this->afterCreating(function (User $user) use ($role) {
            // Tambahkan role customer secara default
            if ($role === 'seller') {
                $user->assignRole('customer');
            }

            // Tambahkan role yang diminta
            $user->assignRole($role);
        });
    }

    public function withRoles(array $roles)
    {
        return $this->afterCreating(function (User $user) use ($roles) {
            // Pastikan role customer ditambahkan jika user memiliki role seller
            if (in_array('seller', $roles) && !in_array('customer', $roles)) {
                $user->assignRole('customer');
            }

            // Tambahkan semua role
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        });
    }
}
