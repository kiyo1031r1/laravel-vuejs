<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function store(Video $video){
        $input = request()->validate([
            'title' => 'required|max:255',
            'about' => 'required',
            'thumbnail' => 'file',
            'video' => 'file',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        $video->thumbnail = $input['thumbnail']->store('thumbnails');
        $video->video = $input['video']->store('videos');
        $video->save();

        return $video;
    }
}
