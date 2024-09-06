<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Crypt;
use App\Services\Aes128\AesCrypt;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'address_id',
        'seller_id',
        'total_price',
        'shipping_price',
        'grand_total',
        'status',
        'payment_va_name',
        'payment_va_number',
        'shipping_service',
        'shipping_number',
        'transaction_number',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Orderitem::class);
    }

    // public function setPaymentVaNameAttribute($value)
    // {
    //     $this->attributes['payment_va_name'] = Crypt::encryptString($value);
    // }

    // // Decrypt the `payment_va_name` attribute when retrieving from the database
    // public function getPaymentVaNameAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    // public function setPaymentVaNumberAttribute($value)
    // {
    //     $this->attributes['payment_va_number'] = Crypt::encryptString($value);
    // }

    // // Decrypt the `payment_va_number` attribute when retrieving from the database
    // public function getPaymentVaNumberAttribute($value)
    // {
    //     return Crypt::decryptString($value);
    // }

    public function setPaymentVaNameAttribute($value)
    {
        $this->attributes['payment_va_name'] = AesCrypt::encrypt($value);
    }

    // Accessor for payment_va_name
    public function getPaymentVaNameAttribute($value)
    {
        return AesCrypt::decrypt($value);
    }

    // Mutator for payment_va_number
    public function setPaymentVaNumberAttribute($value)
    {
        $this->attributes['payment_va_number'] = AesCrypt::encrypt($value);
    }

    // Accessor for payment_va_number
    public function getPaymentVaNumberAttribute($value)
    {
        return AesCrypt::decrypt($value);
    }
}
