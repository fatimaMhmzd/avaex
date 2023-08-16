<?php

namespace Modules\MyAccount\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Damage extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id'];
    protected $table = 'damage';

    protected static function newFactory()
    {
        return \Modules\MyAccount\Database\factories\DamageFactory::new();
    }
}
