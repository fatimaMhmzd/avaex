<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table='driverItem';


}
