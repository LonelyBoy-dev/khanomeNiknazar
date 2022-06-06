<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;
    public function hairstylist()
    {
        return $this->belongsTo(User::class,'hairstylist_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function timings()
    {
        return $this->belongsTo(Timing::class,'timings_id');
    }
}
