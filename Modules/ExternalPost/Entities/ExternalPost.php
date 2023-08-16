<?php

namespace Modules\ExternalPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalPost extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'external_posts';
    protected $guarded = ['id'];


}
