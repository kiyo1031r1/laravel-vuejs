<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    public function index(Video $video){
        return VideoComment::find($video->id);
    }
}
