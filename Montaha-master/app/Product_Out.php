<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Out extends Model
{
    protected $table = 'product_out';

    protected $fillable = ['product_id','po_no','price','customer_id','qty','refund_status','discount', 'subtotal', 'cashier', 'date'];


    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
