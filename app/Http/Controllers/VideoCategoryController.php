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
            'name' => ['required', 'string', 'max:255', 'unique:video_categories'],
            'file_name' => ['required', 'string', 'max:255', 'unique:video_categories']
        ]);

        return VideoCategory::create($request->all());
    }

    public function destroy(VideoCategory $videoCategory){
        return $videoCategory->delete();
    }

    public function getCategory(Request $request){
        return VideoCategory::where('file_name', $request['file_name'])->first();
    }
}