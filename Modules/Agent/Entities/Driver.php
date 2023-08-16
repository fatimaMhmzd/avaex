<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table='drivers';


    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\DriverFactory::new();
    }
}
