<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
   protected $fillable = [
        'type',           
        'management_name',  
        'category_id',
        'amount',
        'date',
        'note',
        'business_day_id',
        'payment_method',
        'is_approved',
        'created_by',
        'updated_by',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
