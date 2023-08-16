<?php

namespace Modules\Compony\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compony extends Model
{
    use HasFactory ,SoftDeletes;
    protected $guarded = ['id'];

    protected $table = 'componies';

}
