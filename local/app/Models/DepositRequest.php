<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositRequest extends Model
{
    use HasFactory;
    public function hairstylist()
    {
        return $this->belongsTo(User::class,'hairstylist_id');
    }
}
