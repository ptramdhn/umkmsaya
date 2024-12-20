<?php

namespace Database\Factories;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition()
    {
        $name = $this->faker->company;

        return [
            'user_id' => $this->getOrAssignSellerUserId(),
            'name' => $name,
            'description' => $this->faker->sentence,
        ];
    }

    private function getOrAssignSellerUserId()
    {
        // Cari user dengan role seller yang belum memiliki toko
        $seller = User::role('seller')
            ->whereDoesntHave('store') // Pastikan user tidak memiliki toko
            ->inRandomOrder()
            ->first();

        if (!$seller) {
            // Jika tidak ada seller yang tersedia, buat user baru dengan role seller dan customer
            $seller = User::factory()->withRole('seller')->create();
        } else {
            // Pastikan user seller juga memiliki role customer
            if (!$seller->hasRole('customer')) {
                $seller->assignRole('customer');
            }
        }

        return $seller->id;
    }
}
