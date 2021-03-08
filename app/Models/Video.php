<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'about',
        'status',
        'thumbnail',
        'thumbnail_name',
        'video',
        'video_name',
        'video_time',
    ];

    public function videoCategories(){
        return $this->belongsToMany(VideoCategory::class);
    }

    public function videoComments(){
        return $this->hasMany(VideoComment::class);
    }

    public function setThumbnailAttribute($value){
        //seederの自動生成用、またはサムネイル削除の場合
        if(strpos($value, '/images') !== false || $value === null){
            $this->attributes['thumbnail'] = $value;
        }
        else{
            //ローカル環境の保存
            if(app()->environment('local')){
                $this->attributes['thumbnail'] = asset('storage/'. $value);
            }
            //本番環境の保存
            elseif(app()->environment('production')){
                $this->attributes['thumbnail'] = $value;
            }
        }
    }

    public function setVideoAttribute($value){
        //seederの自動生成用
        if(strpos($value, '/images') !== false){
            $this->attributes['video'] = $value;
        }

        else{
            //ローカル環境の保存
            if(app()->environment('local')){
                $this->attributes['video'] = asset('storage/'. $value);
            }
            //本番環境の保存
            elseif(app()->environment('production')){
                $this->attributes['video'] = $value;
            }
        }
    }

    public function setVideoTimeAttribute($value){
        if($value >= 60*60) {
            $this->attributes['video_time'] = gmdate("H:i:s", $value);
        }
        elseif($value >= 60*10){
            $this->attributes['video_time'] = gmdate("i:s", $value);
        }
        else{
            $this->attributes['video_time'] = substr(gmdate("i:s", $value), 1);
        }
    }

}
