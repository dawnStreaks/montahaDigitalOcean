<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['product_id','po_no','price','paid_amount','balance','customer_name','mob_no','size','qty','order_status','refund_status','discount', 'subtotal', 'cashier','shoulder','bust','sleeve_conference', 'arm_hole','sldc','sleeve_length','waist_line','hips','length', 'date'];


    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

   
}
