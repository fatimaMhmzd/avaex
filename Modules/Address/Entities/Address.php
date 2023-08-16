<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Address extends Model
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [];
    protected $table='addresses';
    protected $guarded=['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return \Modules\Address\Database\factories\AddressFactory::new();
    }

    public function countryName(){
        return $this->belongsTo('Modules\Address\Entities\Country', 'countryId');
    }
    public function provinceName(){
        return $this->belongsTo('Modules\Address\Entities\Province', 'provinceId');
    }
    public function cityName(){
        return $this->belongsTo('Modules\Address\Entities\City', 'cityId');
    }
}
