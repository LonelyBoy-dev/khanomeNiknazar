<?php

namespace Modules\Posts\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCategory extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Posts\Database\factories\PostCategoryFactory::new();
    }
    public function post()
    {
        return $this->belongsToMany(\Modules\Posts\Entities\Post::class);
    }
}
