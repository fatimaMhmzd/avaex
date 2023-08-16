<?php

namespace Modules\MyAccount\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class CODRequest extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table = '_c_o_d_request';

    protected static function newFactory()
    {
        return \Modules\MyAccount\Database\factories\CODRequestFactory::new();
    }
}
