<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [];
    protected $table='areas';
    protected $guarded=['id'];
    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\AreaFactory::new();
    }
}
