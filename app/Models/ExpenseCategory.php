<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $table = 'expense_categories';
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
    ];
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
