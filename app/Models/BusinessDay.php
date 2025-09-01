<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_date',
        'opening_time',
        'closing_time',
        'status',
        'opening_balance',
        'closing_balance',

        // Opening Balances
        'opening_cash',
        'opening_visa_card',
        'opening_master_card',
        'opening_bkash',
        'opening_nagad',
        'opening_rocket',
        'opening_upay',
        'opening_surecash',
        'opening_online',

        // Closing Balances
        'closing_cash',
        'closing_visa_card',
        'closing_master_card',
        'closing_bkash',
        'closing_nagad',
        'closing_rocket',
        'closing_upay',
        'closing_surecash',
        'closing_online',
    ];

    // helper: check day is open
    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    // relationships (if you want to use)
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
