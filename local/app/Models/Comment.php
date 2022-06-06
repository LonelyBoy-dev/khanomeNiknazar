<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function hairstylist()
    {
        return $this->belongsTo(User::class,'hairstylist_id');
    }
    public function product()
    {
        return $this->belongsTo(User::class,'post_id');
    }
}
