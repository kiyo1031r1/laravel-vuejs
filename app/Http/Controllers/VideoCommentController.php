<?php

namespace App\Http\Controllers;

use App\Models\VideoComment;
use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'comment' => ['required', 'string', 'max:255']
        ]);
        return VideoComment::create($request->all());
    }

    public function getComment(Request $request){
        $comments = VideoComment::with(['user:id,name', 'reVideoComments.user:id,name'])
        ->where('video_id', $request['video_id'])
        ->orderBy('created_at', 'desc')
        ->paginate($request['per_page']);
        return $comments;
    }

    public function destroy(Request $request){
        $comment = VideoComment::find($request['id']);
        $comment->delete();
        return $comment;
    }
}
