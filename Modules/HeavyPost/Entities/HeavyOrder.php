<?php

namespace Modules\HeavyPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeavyOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'heavy_orders';
    protected $guarded = ['id'];


}
