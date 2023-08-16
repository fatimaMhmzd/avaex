<?php

namespace Modules\InternalPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalPost extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'internal_posts';
    protected $guarded = ['id'];
}
