<?php

namespace App\Interfaces;

interface TransactionRepositoryInterface
{
    public function getTransactionDataFromSession();

    public function saveTransactionDataToSession($data);

    public function saveTransaction($data);
    
    public function generateTransactionCode();

    public function calculateTotalAmount($subtotal);

    public function getTransactionByCode($code);

    // public function getTransactionByCodeEmailPhone($code, $email, $phone);
}