<?php

namespace Modules\Pages\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pages extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'pages';
    public function imgSlider(){
        return $this->hasMany('Modules\Slider\Entities\Slider', 'page_id');
    }

}
