<?php

namespace App\Interfaces;

interface StoreRepositoryInterface
{
    public function getStoreBySlug($slug);

    public function getTransactionMonthly($slug);

    public function getBuyerStore($slug);

    public function getProductSold($slug);

    public function getBestSellingProductStore($slug);
}