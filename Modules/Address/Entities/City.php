<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table='cities';
    protected $guarded=['id'];

    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\CityFactory::new();
    }
}
