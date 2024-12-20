<?php

namespace App\Http\Controllers;

use App\Interfaces\StoreRepositoryInterface;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private StoreRepositoryInterface $storeRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository
    )
    {
        $this->storeRepository = $storeRepository;
    }

    public function show($slug)
    {
        $store = $this->storeRepository->getStoreBySlug($slug);
        $transactionsMonthly = $this->storeRepository->getTransactionMonthly($slug);
        $buyerStore = $this->storeRepository->getBuyerStore($slug);
        $productSold = $this->storeRepository->getProductSold($slug);
        $bestProduct = $this->storeRepository->getBestSellingProductStore($slug);

        // dd($bestProduct->total_quantity);

        return view('pages.store.depan', compact('store', 'transactionsMonthly', 'buyerStore', 'productSold', 'bestProduct'));
    }
}
