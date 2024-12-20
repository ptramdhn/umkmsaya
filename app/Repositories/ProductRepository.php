<?php

namespace App\Repositories;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Carbon;
use App\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getProductByUid($uid)
    {
        return Product::where('uid', $uid)->first();
    }

    public function getStoreByIdProduct($id)
    {
        $product = Product::where('id', $id)->first();
        $storeId = $product->store_id;
        $Store = Store::where('id', $storeId)->first();

        return $Store;
    }

    public function getProductPopular()
    {
        $products = Product::withSum(['transactions as total_terjual' => function ($query) {
                        $query->where('created_at', '>=', Carbon::now()->subMonth());
                    }], 'quantity')
                    ->having('total_terjual', '>', 0)
                    ->orderByDesc('total_terjual')
                    ->take(8)
                    ->get();
        
        return $products;
    }
}