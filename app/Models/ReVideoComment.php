<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReVideoComment extends Model
{
    use HasFactory;

    public function videoComment(){
        return $this->belongsTo(VideoComment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}