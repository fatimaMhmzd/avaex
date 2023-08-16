<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoWorker extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'co_worker';
}
