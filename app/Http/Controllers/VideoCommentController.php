<?php

namespace App\Http\Controllers;

use App\Models\VideoComment;
use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    public function index(Video $video){
        return VideoComment::where('video_id', $video->id)->get();
    }
}
