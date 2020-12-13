<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function video_categories(){
        return $this->belongsToMany(VideoCategory::class);
    }

    public function setThumbnailAttribute($value){
        $this->attributes['thumbnail'] = asset('storage/'. $value);
    }

    public function setVideoAttribute($value){
        $this->attributes['video'] = asset('storage/'. $value);
    }

}
