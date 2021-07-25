<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';

    protected $fillable = ['product_out_id','po_no','refund_date','refund_amount','cashier'];


    protected $hidden = ['created_at','updated_at'];

    public function product_out()
    {
        return $this->belongsTo(Product_Out::class);
    }

    

     
}
