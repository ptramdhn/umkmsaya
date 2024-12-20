<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Twilio\Rest\Client;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.serverKey');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $transaction = Transaction::where('code', $orderId)->first();
        $store = Store::where('id', $transaction->store_id)->first();
        $buyer = User::where('id', $transaction->user_id)->first();
        $ownId = User::where('id', $store->user_id)->first();
        $phone = $ownId->phone_number; 
        // dd($phone);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $sid    = env('TWILLIO_SID', '');
        $token  = env('TWILLIO_TOKEN', '');
        $twilio = new Client($sid, $token);

        $messages = 
            "Halo, Owner Toko " . $store->name . "!" . PHP_EOL . PHP_EOL .
            "Pesanan baru telah masuk dengan kode transaksi: " . $transaction->code . "." . PHP_EOL .
            "Mohon segera cek dan proses pesanan ini secepat mungkin agar pembeli tidak perlu menunggu terlalu lama." . PHP_EOL . PHP_EOL .
            "Informasi Detail Pembeli" . PHP_EOL .
            "Nama Pembeli : " . $buyer->name . PHP_EOL . 
            "Email Pembeli : " . $buyer->email . PHP_EOL .
            "Nomor Whatsapp Pembeli : +" . $buyer->phone_number . PHP_EOL . PHP_EOL .
            "Terima kasih atas kerja sama Anda, dan semoga usaha Anda semakin sukses!";

            $transactions = Transaction::where('code', $orderId)->get();
            $counter = 0;
            $productsStockToReduce = []; // Array untuk menyimpan total pengurangan stok per produk
            
            foreach ($transactions as $transaction) {
                switch ($transactionStatus) {
                    case 'capture':
                        if ($request->payment_type == 'credit_card') {
                            if ($request->fraud_status == 'challenge') {
                                $transaction->update(['payment_status' => 'pending']);
                            } else {
                                $transaction->update(['payment_status' => 'success']);
                            }
                        }
                        break;
            
                    case 'settlement':
                        $transaction->update(['payment_status' => 'success']);
                        Cart::where('user_id', $transaction->user_id)->where('product_id', $transaction->product_id)->delete();
            
                        // Tambahkan quantity yang dibeli untuk produk terkait
                        if (isset($productsStockToReduce[$transaction->product_id])) {
                            $productsStockToReduce[$transaction->product_id] += $transaction->quantity;
                        } else {
                            $productsStockToReduce[$transaction->product_id] = $transaction->quantity;
                        }
            
                        if ($counter == 0) {
                            $twilio->messages
                                ->create("whatsapp:+" . $phone, // to
                                    array(
                                        "from" => "whatsapp:+14155238886",
                                        "body" => $messages
                                    )
                                );
                            $counter++;
                        }
            
                        break;
                    case 'pending':
                        $transaction->update(['payment_status' => 'pending']);
                        break;
                    case 'deny':
                        $transaction->update(['payment_status' => 'failed']);
                        break;
                    case 'expire':
                        $transaction->update(['payment_status' => 'expired']);
                        break;
                    case 'cancel':
                        $transaction->update(['payment_status' => 'canceled']);
                        break;
                    default:
                        $transaction->update(['payment_status' => 'unknown']);
                        break;
                }
            }
            
            // Mengurangi stok untuk setiap produk yang terlibat dalam transaksi
            foreach ($productsStockToReduce as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $stockProduct = $product->stock - $quantity;
                    $product->update(['stock' => $stockProduct]);
            
                    // Mengirimkan peringatan jika stok produk kurang dari 5
                    if ($stockProduct < 5) {
                        $messageWarning =
                            "Halo, Owner Toko " . $store->name . "!" . PHP_EOL . PHP_EOL .
                            "Kami ingin memberi tahu bahwa stok produk '" . $product->name . "' hampir habis!" . PHP_EOL .
                            "Segera perbarui stok agar pembeli dapat terus membeli produk tersebut dan toko kamu tetap berjalan lancar!";
            
                        $twilio->messages
                            ->create("whatsapp:+" . $phone, // to
                                array(
                                    "from" => "whatsapp:+14155238886",
                                    "body" => $messageWarning
                                )
                            );
                    }
                }
            }            


        return response()->json(['message' => 'Callback received susccesfully']);
    }
}
