<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class EPengeluaranTiapBulanChart extends ChartWidget
{
    protected static ?string $heading = null;

    // Menetapkan judul secara dinamis saat widget diinisialisasi
    public function mount(): void
    {
        // Menetapkan judul berdasarkan role pengguna yang sedang login
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            self::$heading = 'Keuntungan Setiap Bulan';
        } else {
            self::$heading = 'Total Pengeluaran Bulan Ini';
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
        $userId = Auth::id(); // ID user yang sedang login
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        if (Auth::check() && Auth::user()->hasRole('admin')) {
            // Ambil transaksi untuk tahun ini
            $transactions = Transaction::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->get();

            // Inisialisasi array untuk menyimpan keuntungan per bulan
            $monthlyProfit = [];

            // Group by code untuk memastikan transaksi dengan code yang unik
            $uniqueCodes = $transactions->groupBy('code');

            // Hitung keuntungan per bulan berdasarkan code yang unik
            foreach ($uniqueCodes as $code => $group) {
                // Ambil bulan dari transaksi pertama dalam grup
                $month = Carbon::parse($group->first()->created_at)->month;

                if (!isset($monthlyProfit[$month])) {
                    $monthlyProfit[$month] = 0;
                }

                // Tambahkan 1 transaksi per bulan berdasarkan code yang unik
                $monthlyProfit[$month]++;
            }

            // Biaya layanan per transaksi (misalnya 5000)
            $serviceFee = 5000;

            // Hitung keuntungan per bulan
            foreach ($monthlyProfit as $month => $transactionCount) {
                $monthlyProfit[$month] = $transactionCount * $serviceFee;
            }

            // Format data untuk chart
            $labels = [];
            $data = [];

            // Pastikan bulan 1 sampai 12 muncul di chart meskipun tidak ada transaksi
            for ($month = 1; $month <= 12; $month++) {
                $labels[] = Carbon::create()->month($month)->format('F'); // Nama bulan
                $data[] = $monthlyProfit[$month] ?? 0; // Keuntungan bulan tersebut, jika tidak ada set ke 0
            }

            return [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Keuntungan Bulanan',
                        'data' => $data,
                        'backgroundColor' => 'rgba(46, 204, 113, 0.2)', // Warna hijau cerah untuk area
                        'borderColor' => '#27ae60', // Warna hijau solid untuk garis
                        'fill' => true,
                    ],
                ],
            ];
        } else {
            // Query data pengeluaran per bulan untuk user yang sedang login
            $monthlyExpenses = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Siapkan data untuk chart
            $labels = $monthlyExpenses->pluck('month')->map(function ($month) {
                return Carbon::create()->month($month)->translatedFormat('F'); // Format nama bulan (e.g., Januari, Februari)
            })->toArray();

            $data = $monthlyExpenses->pluck('total')->toArray();

            return [
                'datasets' => [
                    [
                        'label' => 'Total Pengeluaran Setiap Bulan',
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
