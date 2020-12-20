<?php

namespace App\Http\Controllers;

use App\Models\ReVideoComment;
use App\Models\VideoComment;
use Illuminate\Http\Request;

class ReVideoCommentController extends Controller
{
    public function index(VideoComment $comment){
        return ReVideoComment::where('video_comment', $comment->id)->get();
    }
}
