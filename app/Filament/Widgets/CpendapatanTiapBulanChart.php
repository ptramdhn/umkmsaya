<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CpendapatanTiapBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Bulanan Toko';

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

        // Siapkan array untuk total pendapatan tiap bulan
        $monthlyRevenue = array_fill(0, 12, 0); // Isi awal semua bulan 0

        // Dapatkan transaksi berdasarkan toko dan tahun ini
        $transactions = Transaction::where('store_id', $storeId)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        // Hitung total pendapatan tiap bulan
        foreach ($transactions as $transaction) {
            $month = Carbon::parse($transaction->created_at)->month - 1; // Bulan sebagai indeks (0-11)
            $monthlyRevenue[$month] += $transaction->total_amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Bulanan',
                    'data' => $monthlyRevenue,
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
