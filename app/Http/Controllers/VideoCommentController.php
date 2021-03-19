<?php

namespace App\Http\Controllers;

use App\Models\VideoComment;
use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'video_comment' => ['required', 'string', 'max:255']
        ]);
        VideoComment::create($request->all());
    }

    public function update(VideoComment $video_comment){
        $input = request()->validate([
            'edit_video_comment' => ['required', 'string', 'max:255']
        ]);
        $video_comment->comment = $input['edit_video_comment'];
        $video_comment->save();
    }

    public function getComment(Request $request){
        $comments = VideoComment::with(['user:id,name', 'reVideoComments.user:id,name'])
        ->where('video_id', $request['video_id'])
        ->orderBy('created_at', 'desc')
        ->paginate($request['per_page']);
        return $comments;
    }

    public function destroy(VideoComment $video_comment){
        $video_comment->delete();
    }
}
