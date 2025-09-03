<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseArticle extends Model
{
    // Mass assignable fields
    protected $fillable = [
        'name',
        'notes',
        'status',
        'is_approved',
        'expense_category_id',
        'created_by',
        'updated_by',
    ];

    // Relation to User who created the article
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation to User who last updated the article
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relation to Expense Category
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
