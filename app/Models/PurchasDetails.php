<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase2;

class PurchasDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productSize(){
        return $this->belongsTo(ProductSize::class,'product_id','id');
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function unit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
    public function purchase2(){
        return $this->belongsTo(Purchase2::class,'purchase_id','id');
    }
}
