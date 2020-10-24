<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoCategoryController extends Controller
{
    public function index(){
        return VideoCategory::get(['id', 'name']);
    }

    public function store(Request $request){
        return VideoCategory::create($request->all());
    }

    public function destroy(VideoCategory $videoCategory){
        return $videoCategory->delete();
    }
}