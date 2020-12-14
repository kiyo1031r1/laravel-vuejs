<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideoController extends Controller
{
    public function store(Video $video){
        $input = request()->validate([
            'title' => 'required|max:255',
            'about' => 'required',
            'thumbnail' => 'file',
            'video' => 'file',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        $video->thumbnail = $input['thumbnail']->store('thumbnails');
        $video->video = $input['video']->store('videos');
        $video->save();
        $video->video_categories()->attach(request('category'));

        return $video;
    }

    public function search(Request $request){
        $query = Video::query();

        $data = $request->all();
        $search = $data['search'];
        $sort = $data['sort'];

        //検索
        $title = $search['title'];
        if($title){
            $this->searchWord($title, 'title', $query);
        }

        return $query->paginate($sort['per_page']);

    }

    private function searchWord($word, $column, $query){
        $word_split = mb_convert_kana($word, 's');
        $word_split = preg_split('/[\s]+/', $word_split, 0, PREG_SPLIT_NO_EMPTY);

        foreach($word_split as $value){
            $query->where($column, 'like', '%'.$value.'%');
        }
    }
}
