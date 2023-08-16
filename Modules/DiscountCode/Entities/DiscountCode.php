<?php

namespace Modules\DiscountCode\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'discount_codes';

    protected $guarded=['id'];

    protected static function newFactory()
    {
        return \Modules\DiscountCode\Database\factories\DiscountCodeFactory::new();
    }
}
