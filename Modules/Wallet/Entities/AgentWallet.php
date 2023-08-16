<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentWallet extends Model
{
    use  HasFactory, SoftDeletes;

    protected $fillable = [];
    protected $table='agent_wallet';
    protected $guarded=['id'];
}
