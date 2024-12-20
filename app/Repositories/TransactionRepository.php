<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Product;
use App\Models\Room;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionDataFromSession()
    {
        return session()->get('transaction');
    }

    public function saveTransactionDataToSession($data)
    {
        $transaction = session()->get('transaction', []);

        foreach ($data as $key => $value) {
            $transaction[$key] = $value;
        }

        session()->put('transaction', $transaction);
    }

    public function saveTransaction($data)
    {
        $product = Product::find($data['product_id']);
        $data = $this->prepareTransactionData($data, $product);
        // dd($data);
        $transaction = Transaction::create($data);

        session()->forget('transaction');

        return $transaction;
    }

    public function prepareTransactionData($data, $product)
    {
        $data['code'] = $this->generateTransactionCode();
        $data['payment_status'] = 'pending';
        $data['total_amount'] = $this->calculateTotalAmount($product->price, $data['quantity']);

        return $data;
    }

    public function generateTransactionCode()
    {
        return 'UMKM' . rand(100000, 999999);
    }

    public function calculateTotalAmount($subtotal)
    {
        // $subtotal = $price * $quantity;
        $tax = $subtotal * 0.11;
        
        return $subtotal + $tax + 5000;
    }

    public function getTransactionByCode($code)
    {
        $transaction = Transaction::where('code', $code)->first();

        return $transaction;
    }
}