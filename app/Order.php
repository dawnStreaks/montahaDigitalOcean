<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['product_id','po_no','price','customer_name','qty','order_status','refund_status','discount', 'subtotal', 'cashier', 'date'];


    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

   
}
