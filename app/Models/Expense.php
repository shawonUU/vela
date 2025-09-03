<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category_id',
        'article_id',
        'pay_to',
        'amount',
        'date',
        'note',
        'business_day_id',
        'payment_method',
        'is_approved',
        'created_by',
        'updated_by',
    ];

    /**
     * Relation to ExpenseArticle
     */
    public function article()
    {
        return $this->belongsTo(ExpenseArticle::class, 'article_id');
    }

    /**
     * Relation to ExpenseCategory
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    /**
     * Relation to User who created the expense
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relation to User who last updated the expense
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
