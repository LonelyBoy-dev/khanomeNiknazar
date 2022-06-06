<?php

namespace Modules\Posts\Entities;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\PostExam;
use App\Models\PostModule;
use App\Models\PostNotexam;
use App\Models\PostScore;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Posts\Database\factories\PostFactory::new();
    }
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
    public function postcategories()
    {
        return $this->belongsToMany(PostCategory::class);
    }
    public function postexams()
    {
        return $this->belongsToMany(PostExam::class);
    }
    public function postmodules()
    {
        return $this->belongsToMany(PostModule::class);
    }
    public function postnotexams()
    {
        return $this->belongsToMany(PostNotexam::class);
    }
    public function postscores()
    {
        return $this->belongsToMany(PostScore::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
