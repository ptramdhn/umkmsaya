<?php

use App\Livewire\Masuk;
use App\Livewire\Register;
use App\Livewire\MasukStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
})->name('home');

// Route::get('/produk-terbaik', function () {
//     return view('pages.produkTerbaik');
// })->name('produkTerbaik');

Route::get('/produk-terbaik', [ProductController::class, 'produkTerbaik'])->name('produkTerbaik');

Route::get('/daftar', function () {
    return view('pages.register
    ');
})->name('daftar');

Route::get('/masuk', function () {
    return view('pages.masuk');
})->name('masuk');

Route::get('/masuk/store/{slug}', MasukStore::class)->name('masukStore');

Route::get('/daftar-toko', function () {
    return view('pages.bukaToko');
})->name('daftar.toko');

Route::get('/keranjang/slug/produk/uid', function () {
    return view('pages.store.keranjang');
})->name('keranjang.toko');

Route::get('/toko/{slug}', [StoreController::class, 'show'])->name('store.show');
Route::get('/produk/{uid}', [ProductController::class, 'show'])->name('product.show');
Route::get('/keranjang/input/{id}', [TransactionController::class, 'cart'])->name('keranjang.input');
Route::get('/keranjang/toko/{slug}', [TransactionController::class, 'showCart'])->name('keranjang.show');
Route::get('/keranjang/hapus/{id}', [ProductController::class, 'RemoveFromCart'])->name('cart.remove');
Route::post('/toko/{slug}/pembayaran', [TransactionController::class, 'payment'])->name('store.payment');
Route::get('/pembelian-berhasil', [TransactionController::class, 'success'])->name('store.success');

// Route::get('/toko/{slug}/pembelian-berhasi/{code}', function () {
//     return view('pages.store.success');
// })->name('toko.success');

// Route::get('/daftar', Register::class)->name('daftar');
// Route::post('/daftar', [Register::class, 'save'])->name('daftar.save');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/logout/umkm', function () {
    Auth::logout();
    return redirect('/masuk');
})->name('logout.custom');

Route::get('/seller', function() {
    return '<h1>Hello Seller!</h1>';
})->middleware(['auth', 'verified'])->name('seller');

require __DIR__.'/auth.php';
