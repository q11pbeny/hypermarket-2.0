<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_code',
        'birth_date',
        'gender',
        'total_purchases',
        'total_orders',
        'address',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
        'total_purchases' => 'decimal:2'
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // محاسبه میانگین خرید
    public function getAverageOrderValueAttribute()
    {
        if ($this->total_orders == 0) return 0;
        return $this->total_purchases / $this->total_orders;
    }

    // بررسی مشتری وفادار
    public function getIsLoyalCustomerAttribute()
    {
        return $this->total_orders >= 5 && $this->total_purchases >= 1000000;
    }
}
