<?php

namespace Modules\InUrbanePost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeykOrder extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\InUrbanePost\Database\factories\PeykOrderFactory::new();
    }
}
