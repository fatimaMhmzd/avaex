<?php

namespace Modules\InternalPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'internal_post_order';
    protected $guarded = ['id'];

    protected $casts = [
        "serviceNumberParcel" => "string",
        "servicePartnumber" => "string",
    ];

}
