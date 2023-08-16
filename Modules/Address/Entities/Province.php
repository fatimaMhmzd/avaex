<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected $table='provinces';
    protected $guarded=['id'];


    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\ProvinceFactory::new();
    }
}
