<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
    }

    public function show($uid)
    {
        $product = $this->productRepository->getProductByUid($uid);

        return view('pages.store.product', compact('product'));
    }

    public function produkTerbaik()
    {
        $products = $this->productRepository->getProductPopular();

        return view('pages.produkTerbaik', ['products' => $products ?? collect()]);
    }

    public function RemoveFromCart($id)
    {
        Cart::where('user_id', Auth::user()->id)->where('product_id', $id)->delete();
        $product = Product::where('id', $id)->first();
        $store = $product->store->slug;

        return redirect()->route('keranjang.show', $store)
                        ->with('status', 'success')
                        ->with('message', 'Produk Berhasil Dihapus Dari Keranjang');
    }
}
