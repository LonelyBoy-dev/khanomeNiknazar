<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostNotexam extends Model
{
    use HasFactory;
    public function post()
    {
        return $this->belongsToMany(\Modules\Posts\Entities\Post::class);
    }
}
