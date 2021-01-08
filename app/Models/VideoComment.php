<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'video_id',
        'user_id',
    ];

    public function video(){
        return $this->belongsTo(Video::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reVideoComments(){
        return $this->hasMany(ReVideoComment::class);
    }
}
