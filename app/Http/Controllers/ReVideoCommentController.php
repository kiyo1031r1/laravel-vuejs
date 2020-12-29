<?php

namespace App\Http\Controllers;

use App\Models\ReVideoComment;
use App\Models\VideoComment;
use Illuminate\Http\Request;

class ReVideoCommentController extends Controller
{
    public function destroy(Request $request){
        $comment = ReVideoComment::find($request['id']);
        $comment->delete();
        return $comment;
    }
}
