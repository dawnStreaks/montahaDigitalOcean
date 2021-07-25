<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale_New extends Model
{
    protected $table = 'sales_new';

    protected $fillable = ['po_no','customer_id','total_amount','date', 'refund_status','cashier'];


    protected $hidden = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}