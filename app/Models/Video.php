<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function videoCategory(){
        return $this->belongsToMany(VideoCategory::class);
    }

    public function videoComments(){
        return $this->hasMany(VideoComment::class);
    }

    public function setThumbnailAttribute($value){
        //seederの自動生成用
        if(strpos($value, 'https://') !== false || strpos($value, 'http://') !== false || strpos($value, '/images') !== false){
            $this->attributes['thumbnail'] = $value;
        }
        //サムネイル削除の場合
        elseif($value === null){
            $this->attributes['thumbnail'] = $value;
        }
        //通常保存用
        else{
            $this->attributes['thumbnail'] = asset('storage/'. $value);
        }
    }

    public function setVideoAttribute($value){
        //seederの自動生成用
        if(strpos($value, 'https://') !== false || strpos($value, 'http://') !== false || strpos($value, '/images') !== false){
            $this->attributes['video'] = $value;
        }
        //通常保存用
        else{
            $this->attributes['video'] = asset('storage/'. $value);
        }
    }

    public function setVideoTimeAttribute($value){
        if($value > 60*60) {
            $this->attributes['video_time'] = gmdate("H:i:s", $value);
        }
        elseif($value > 60*10){
            $this->attributes['video_time'] = gmdate("i:s", $value);
        }
        else{
            $this->attributes['video_time'] = substr(gmdate("i:s", $value), 1);
        }
    }

}
