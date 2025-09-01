<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{    
    protected $fillable = [
        'date',
        'cashier_id',
        'opening_balance',
        'total_sales',
        'total_expenses',
        'closing_balance',
        'status',
    ];

    // Cashier relation
    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }
}
