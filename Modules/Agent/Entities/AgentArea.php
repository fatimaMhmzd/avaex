<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentArea extends Model
{
    use  HasFactory,SoftDeletes;

    protected $table='agent_areas';
    protected $guarded=['id'];
    
    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentAreaFactory::new();
    }
}
