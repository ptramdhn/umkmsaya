<?php

namespace App\Filament\Widgets;

use App\Models\Store;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AWeeklyTransactions extends BaseWidget
{
    protected function getStats(): array
    {
        // Carbon::setTimezone('Asia/Jakarta');

        $user = Auth::user();
        $userId = $user->id;

        $storeId = $user->store->id ?? null;

        $startOfWeek = Carbon::now()->startOfWeek()->setTimezone('UTC');
        $endOfWeek = Carbon::now()->endOfWeek()->setTimezone('UTC');

        $startOfMonth = Carbon::now()->startOfMonth()->setTimezone('UTC');
        $endOfMonth = Carbon::now()->endOfMonth()->setTimezone('UTC');

        if ($user->hasRole('admin')) {
            $totalUsers = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();
            
            $totalAmountMonthly = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');

            $totalToko = Store::query()->count();

            return [
                Stat::make('Jumlah Transaksi Bulan Ini', 'Rp ' . number_format($totalAmountMonthly, 0, ',', '.')),
                Stat::make('Jumlah User Yang Terdaftar', $totalUsers),
                Stat::make('Jumlah Toko Yang Terdaftar', $totalToko),
            ];
        }

        if (!$storeId) {
            $totalTransactionsWeekly = Transaction::where('user_id', $userId)
                                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
            $totalAmountWeekly = Transaction::where('user_id', $userId)
                                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount');
            $totalAmountMonthly = Transaction::where('user_id', $userId)
                                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');

            return [
                Stat::make('Total Transaksi Minggu Ini', $totalTransactionsWeekly),
                Stat::make('Jumlah Pembelian Minggu Ini', 'Rp ' . number_format($totalAmountWeekly, 0, ',', '.')),
                Stat::make('Jumlah Pembelian Bulan Ini', 'Rp ' . number_format($totalAmountMonthly, 0, ',', '.')),
            ];
        }   

        $totalTransactionsWeekly = Transaction::where('store_id', $storeId)
                                    ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();
        $totalAmountWeekly = Transaction::where('store_id', $storeId)
                                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount');
        $totalAmountMonthly = Transaction::where('store_id', $storeId)
                                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_amount');

        return [
            Stat::make('Total Transaksi Minggu Ini', $totalTransactionsWeekly),
            Stat::make('Total Pendapatan Minggu Ini', 'Rp ' . number_format($totalAmountWeekly, 0, ',', '.')),
            Stat::make('Total Pendapatan Bulan Ini', 'Rp ' . number_format($totalAmountMonthly, 0, ',', '.')),
        ];
    }
}
