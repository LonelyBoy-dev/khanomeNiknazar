<?php

namespace Modules\Posts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostPostCategory extends Model
{
    use HasFactory;

    protected $fillable = [],$table="post_post_category";

    protected static function newFactory()
    {
        return \Modules\Posts\Database\factories\PostPostCategoryFactory::new();
    }
}
