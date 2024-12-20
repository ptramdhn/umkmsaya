<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Transaction;
use App\Interfaces\StoreRepositoryInterface;

class StoreRepository implements StoreRepositoryInterface
{
    public function getStoreBySlug($slug)
    {
        return Store::where('slug', $slug)->first();
    }

    public function getTransactionMonthly($slug)
    {
        $storeId = Store::where('slug', $slug)->first()->id;

        // Dapatkan transaksi berdasarkan toko dan bulan ini
        $transactions = Transaction::where('store_id', $storeId)
        ->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->count(); // Hitung jumlah transaksi

        return $transactions;
    }

    public function getBuyerStore($slug)
    {
        $storeId = Store::where('slug', $slug)->first()->id;

        // Dapatkan jumlah pembeli unik berdasarkan user_id
        $uniqueBuyers = Transaction::where('store_id', $storeId)
        ->distinct('user_id') // Pilih user_id yang unik
        ->count('user_id');   // Hitung jumlah pembeli unik

        return $uniqueBuyers;
    }

    public function getProductSold($slug)
    {
        $storeId = Store::where('slug', $slug)->first()->id;

        // Hitung total produk terjual berdasarkan store_id
        $totalSold = Transaction::where('store_id', $storeId)
        ->sum('quantity'); // Menghitung total quantity

        return $totalSold;
    }

    public function getBestSellingProductStore($slug)
    {
        $storeId = Store::where('slug', $slug)->first()->id;

        // Dapatkan bulan dan tahun saat ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Query untuk mendapatkan produk paling laku
        $bestSellingProduct = Transaction::where('store_id', $storeId)
            ->whereYear('created_at', $currentYear) // Filter transaksi tahun ini
            ->whereMonth('created_at', $currentMonth) // Filter transaksi bulan ini
            ->select('product_id', Transaction::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id') // Grup berdasarkan produk
            ->orderByDesc('total_quantity') // Urutkan berdasarkan jumlah terjual
            ->first(); // Ambil produk pertama (terbanyak)

        return $bestSellingProduct;
    }
}