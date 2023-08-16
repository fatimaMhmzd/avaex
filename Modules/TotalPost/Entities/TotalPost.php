<?php

namespace Modules\TotalPost\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TotalPost extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'total_posts';

    public function addressAt(){
        return $this->belongsTo('Modules\Address\Entities\Address', 'addressId');
    }
}
