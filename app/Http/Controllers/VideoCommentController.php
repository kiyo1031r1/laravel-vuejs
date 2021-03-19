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
        $video_comment->video_comment = $input['edit_video_comment'];
        $video_comment->save();
    }

    public function getComment(Request $request){
        $comments = VideoComment::with(['user:id,name', 'reVideoComments.user:id,name'])
        ->where('video_id', $request['video_id'])
        ->orderBy('created_at', 'desc')
        ->orderBy('id', 'desc') //同じ時間だった場合、同じものが再度選択される場合がある為
        ->paginate($request['per_page']);
        return $comments;

    }

    public function destroy(VideoComment $video_comment){
        $video_comment->delete();
    }
}
