<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DPengeluaranTiapMingguChart extends ChartWidget
{
    protected static ?string $heading = null;

    // Menetapkan judul secara dinamis saat widget diinisialisasi
    public function mount(): void
    {
        // Menetapkan judul berdasarkan role pengguna yang sedang login
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            self::$heading = 'Jumlah Transaksi Setiap Bulan';
        } else {
            self::$heading = 'Pengeluaran Minggu Ini';
        }
    }

    public static function canView(): bool
    {
        $user = Auth::user();
        $store = $user->store->id ?? null;

        if ($user->hasRole('seller')) {
            return false;
        }

        return true;
    }

    protected function getData(): array
    {
        $userId = Auth::user()->id; // ID user yang sedang login
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // Query data pengeluaran per bulan
            $monthlyExpenses = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])
                                ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                                ->groupBy('month')
                                ->orderBy('month')
                                ->get();

            // Siapkan data untuk chart
            $labels = $monthlyExpenses->pluck('month')->map(function ($month) {
            return Carbon::createFromFormat('m', $month)->format('F'); // Nama bulan
            })->toArray();
            $data = $monthlyExpenses->pluck('total')->toArray();

            return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi Setiap Bulan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 71, 0.2)', // Warna merah cerah untuk area
                    'borderColor' => '#ff0000', // Warna merah solid untuk garis
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
            ];
        } else {
            // Query data pengeluaran per minggu untuk user yang sedang login
            $weeklyExpenses = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->selectRaw('WEEK(created_at) as week, SUM(total_amount) as total')
            ->groupBy('week')
            ->orderBy('week')
            ->get();

            // Siapkan data untuk chart
            $labels = $weeklyExpenses->pluck('week')->map(fn($week) => "Minggu ke-$week")->toArray();
            $data = $weeklyExpenses->pluck('total')->toArray();

            return [
                'datasets' => [
                    [
                        'label' => 'Total Pengeluaran Setiap Minggu',
                        'data' => $data,
                        'backgroundColor' => 'rgba(255, 99, 71, 0.2)', // Warna merah cerah untuk area
                        'borderColor' => '#ff0000', // Warna merah solid untuk garis
                        'fill' => true,
                    ],
                ],
                'labels' => $labels,
            ];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }
}
