<?php

namespace App\Models;

use App\Models\Postcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
    public function postcategories()
    {
        return $this->belongsToMany(\App\Models\PostCategory::class);
    }
}
