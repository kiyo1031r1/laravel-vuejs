<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoCategoryController extends Controller
{
    public function index(){
        return VideoCategory::get(['id', 'name', 'file_name']);
    }

    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:video_categories']
        ]);

        return VideoCategory::create($request->all());
    }

    public function destroy(VideoCategory $videoCategory){
        return $videoCategory->delete();
    }
}