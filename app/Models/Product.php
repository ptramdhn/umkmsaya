<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'image',
        'name',
        'description',
        'price',
        'stock'
    ];

    protected static function boot()
    {
        parent::boot();

        // Event untuk mengisi UID sebelum record disimpan
        static::creating(function ($model) {
            $model->uid = (string) Str::uuid(); // Generate UUID
        });
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}
