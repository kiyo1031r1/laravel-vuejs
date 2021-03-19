<?php

namespace App\Http\Controllers;

use App\Models\ReVideoComment;
use Illuminate\Http\Request;

class ReVideoCommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            're_video_comment' => ['required', 'string', 'max:255']
        ]);
        ReVideoComment::create($request->all());
    }

    public function update(ReVideoComment $re_video_comment){
        $input = request()->validate([
            'edit_re_video_comment' => ['required', 'string', 'max:255']
        ]);
        $re_video_comment->re_video_comment = $input['edit_re_video_comment'];
        $re_video_comment->save();
    }

    public function destroy(ReVideoComment $re_video_comment){
        $re_video_comment->delete();
    }
}
