<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_In extends Model
{
    protected $table = 'product_in';

    protected $fillable = ['product_id','user_name','qty','date'];

    protected $hidden = ['created_at','updated_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    

    // public function Supplier()
    // {
    //     return $this->belongsTo(Supplier::class);
    // }


}
