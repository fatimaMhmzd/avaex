<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Agent extends Model
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $table='agent';
    protected $guarded=['id'];

    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentFactory::new();
    }
}
