<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'contact_us';
}
