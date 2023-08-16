<?php

namespace Modules\PostupServices\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Packaging extends Model
{
    use HasFactory;

    protected $fillable = [];
    protected $table='packagings';
}
