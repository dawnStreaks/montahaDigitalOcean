<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temp_Sale extends Model
{
    protected $table = 'temp_sales';

    protected $fillable = ['po_no','product_id','price','qty','discount','subtotal','date'];

    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
