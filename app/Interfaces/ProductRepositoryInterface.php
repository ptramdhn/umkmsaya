<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function getProductByUid($uid);

    public function getStoreByIdProduct($id);

    public function getProductPopular();
}