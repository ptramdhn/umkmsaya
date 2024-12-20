<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BProductsSellingChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan Produk Bulan Ini';
    protected static string $color = 'danger';

    public static function canView(): bool
    {
        $user = Auth::user();
        $store = $user->store;

        if ($store == null) {
            return false;
        }

        return true;
    }

    protected function getData(): array
    {
        $user = Auth::user();

        // Ambil ID toko milik user
        $storeId = $user->store->id ?? null;

        if (!$storeId) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Tentukan periode bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Query produk beserta jumlah yang terjual
        $products = Product::where('store_id', $storeId)
            ->withSum(['transactions as total_sold' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }], 'quantity')
            ->get();

        // Siapkan data untuk chart
        $labels = $products->pluck('name')->toArray();
        $data = $products->pluck('total_sold')->map(fn($value) => $value ?? 0)->toArray();

        // Siapkan warna untuk setiap produk
        $colors = array_map(function () {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF)); // Generate warna acak
        }, range(0, count($data) - 1));

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
