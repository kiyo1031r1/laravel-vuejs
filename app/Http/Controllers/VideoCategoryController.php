<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoCategoryRequest;
use Illuminate\Validation\ValidationException;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoCategoryController extends Controller
{
    public function index(){
        return VideoCategory::get(['id', 'name', 'file_name']);
    }

    public function store(StoreVideoCategoryRequest $request){
        $input = $request->validated();
        VideoCategory::create(['name' => $input['name'], 'file_name' => $input['name']]);
    }

    public function destroy(VideoCategory $videoCategory){
        $categories = ['php','ruby','javascript','python','csharp','laravel','rails','vuejs','django'];

        if(in_array($videoCategory->file_name, $categories)) {
            throw ValidationException::withMessages([
                'delete_category' => ['そのカテゴリーは削除できません。']
            ]);
        }
        $videoCategory->delete();
    }

    public function exist(Request $request){
        return VideoCategory::where('file_name', $request['file_name'])->firstOrFail();
    }
}