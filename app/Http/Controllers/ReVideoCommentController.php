<?php

namespace App\Http\Controllers;

use App\Models\ReVideoComment;
use App\Models\VideoComment;
use Illuminate\Http\Request;

class ReVideoCommentController extends Controller
{
    public function store(Request $request){
        $request->validate([
            're_comment' => ['required', 'string', 'max:255']
        ]);
        return ReVideoComment::create($request->all());
    }

    public function destroy(Request $request){
        $comment = ReVideoComment::find($request['id']);
        $comment->delete();
        return $comment;
    }
}
