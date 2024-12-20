<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;

class TransactionController extends Controller
{
    private ProductRepositoryInterface $productRepository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        TransactionRepositoryInterface $transactionRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function cart($id)
    {
        // $this->transactionRepository->saveTransactionDataToSession($request->all());
        $user = Auth::user()->id;
        $cart = Cart::where('user_id', $user)->where('product_id', $id)->first();
        $Product = Product::where('id', $id)->first();
        $stockProduct = $Product->stock;

        if ($cart) {
            return redirect()->route('product.show', $Product->uid)
                        ->with('status', 'warning')
                        ->with('message', 'Produk Sudah Ada Dalam Keranjang!');
        }

        if ($stockProduct < 1) {
            // Hapus semua cart yang memiliki product_id tersebut
            Cart::where('product_id', $id)->delete();

            return redirect()->route('product.show', $Product->uid)
                        ->with('status', 'error')
                        ->with('message', 'Produk Habis!');
        }

        Cart::create([
            'user_id' => $user,
            'product_id' => $id
        ]);
  
        // $store = $this->productRepository->getStoreByIdProduct($id);
        // $storeSlug = $store->slug;
        // dd($this->transactionRepository->saveTransactionDataToSession($request->all()));

        return redirect()->route('product.show', $Product->uid)
                        ->with('status', 'success')
                        ->with('message', 'Produk Berhasil Ditambahkan Ke Keranjang!');
    }

    public function showCart($slug)
    {
        $store = Store::where('slug', $slug)->first();
        $dataCarts = Cart::with('product')
                    ->whereHas('product', function ($query) use ($store) {
                        $query->where('store_id', $store->id);
                    })
                    ->where('user_id', Auth::user()->id)
                    ->get();

        return view('pages.store.keranjang', compact('dataCarts', 'store'));
    }

    public function payment(Request $request, $slug)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $store = Store::where('slug', $slug)->first();
        $carts = Cart::with('product')
                    ->whereHas('product', function ($query) use ($store) {
                        $query->where('store_id', $store->id);
                    })
                    ->where('user_id', Auth::user()->id)
                    ->get();
        $code = $this->transactionRepository->generateTransactionCode();
        $quantities = $request->input('quantity');
        $totalQuantity = 0;
        $grandTotal = 0;

        foreach ($carts as $cart) {
            // Cek apakah ada quantity yang dikirimkan untuk produk ini
            $productId = $cart->product->id;
            
            // Pastikan ada quantity untuk produk ini di array $quantities
            if (isset($quantities[$productId])) {
                // Ambil quantity untuk produk ini
                $quantity = $quantities[$productId];
    
                // Buat transaksi untuk produk ini
                Transaction::create([
                    'code' => $code, // Generate code transaksi jika diperlukan
                    'store_id' => $store->id,
                    'product_id' => $productId,
                    'user_id' => Auth::user()->id,
                    'payment_status' => 'pending',
                    'quantity' => $quantity,
                    'total_amount' => $cart->product->price * $quantity,
                ]);

                $grandTotal += $cart->product->price * $quantity;
                $totalQuantity += $quantity;
            }
        }

        $subtotal = $this->transactionRepository->calculateTotalAmount($grandTotal);

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' =>$code,
                'gross_amount' => $subtotal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number,
            ],
        ];

        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return redirect($paymentUrl);
    }

    public function success(Request $request)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($request->order_id);

        if (!$transaction) {
            return redirect()->route('home');
        }

        return view('pages.store.success', compact('transaction'));
    }
}
