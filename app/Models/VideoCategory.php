<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name'
    ];

    public function videos(){
        return $this->belongsToMany(Video::class);
    }
}
