<?php

namespace Modules\Discount\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerDiscount extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'customer_discounts';
    protected static function newFactory()
    {
        return \Modules\Discount\Database\factories\CustomerDiscountFactory::new();
    }
}
