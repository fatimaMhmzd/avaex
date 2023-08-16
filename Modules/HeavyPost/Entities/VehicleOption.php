<?php

namespace Modules\HeavyPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleOption extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'vehicle_options';
    protected $guarded = ['id'];
}
