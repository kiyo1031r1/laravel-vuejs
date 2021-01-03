<?php

namespace App\Http\Controllers;

use App\Models\VideoComment;
use Illuminate\Http\Request;

class VideoCommentController extends Controller
{
    public function destroy(Request $request){
        $comment = VideoComment::find($request['id']);
        $comment->delete();
        return $comment;
    }
}
