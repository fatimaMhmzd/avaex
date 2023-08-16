<?php

namespace Modules\Compony\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComponyService extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'compony_services';
    public function compony(){
        return $this->belongsTo('Modules\Compony\Entities\Compony', 'componyId');
    }
    public function service(){
        return $this->belongsTo('Modules\Compony\Entities\Service', 'serviceId');
    }
    public function componyTypePost(){
        return $this->belongsTo('Modules\Compony\Entities\ComponyTypePost', 'componyTypeId');
    }

}
