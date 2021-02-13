<?php

namespace App\Http\Controllers;

use App\Models\ReVideoComment;
use Illuminate\Http\Request;

class ReVideoCommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            're_comment' => ['required', 'string', 'max:255']
        ]);
        ReVideoComment::create($request->all());
    }

    public function destroy(ReVideoComment $re_video_comment){
        $re_video_comment->delete();
    }
}
