<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'blog_groups';

    protected $guarded=['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogGroupFactory::new();
    }
}
