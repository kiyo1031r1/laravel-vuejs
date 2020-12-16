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
            'thumbnail' => 'required',
            'thumbnail_name' => 'required',
            'video' => 'required',
            'video_name' => 'required',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        $video->thumbnail = $input['thumbnail']->store('thumbnails');
        $video->thumbnail_name = $input['thumbnail_name'];
        $video->video = $input['video']->store('videos');
        $video->video_name = $input['video_name'];
        $video->save();
        $video->videoCategory()->attach(request('category'));

        return $video;
    }

    public function show(Video $video){
        return Video::with('videoCategory')->find($video->id);
    }

    public function update(Video $video){
        $input = request()->validate([
            'title' => 'required|max:255',
            'about' => 'required',
            'thumbnail_name' => 'required',
            'video_name' => 'required',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        if(request('thumbnail')){
            $video->thumbnail = request('thumbnail')->store('thumbnails');
        }
        if(request('video')){
            $video->video = request('video')->store('videos');
        }
        $video->thumbnail_name = $input['thumbnail_name'];
        $video->video_name = $input['video_name'];
        $video->save();
        $video->videoCategory()->sync(request('category'));

        return $video;
    }

    public function destroy(Video $video){
        if($video->thumbnail || $video->video){
            $this->deleteFile($video->thumbnail, $video->video);
        }

        $video->delete();
        return $video;
    }

    private function deleteFile($thumbnail, $video){
        $thumbnail_url = env('APP_URL').'/storage/thumbnails/';
        $video_url = env('APP_URL').'/storage/videos/';
        
        $thumbnail_file_path = storage_path('app/public/thumbnails/');
        $video_file_path = storage_path('app/public/videos/');

        //ファイル名を取得
        $thumbnail_file_name = str_replace($thumbnail_url, '', $thumbnail);
        $video_file_name = str_replace($video_url, '', $video);

        // ダミーデータを削除しない処理
        $sample_thumbnail = 'A_thumbnail_sample.jpeg';
        $sample_video = 'A_video_sample.qt';
        if($thumbnail_file_name === $sample_thumbnail || $video_file_name === $sample_video){
            return;
        }

        //ファイル削除
        if(is_file($thumbnail_file_path.$thumbnail_file_name)){
            unlink($thumbnail_file_path.$thumbnail_file_name);
        }
        if(is_file($video_file_path.$video_file_name)){
            unlink($video_file_path.$video_file_name);
        }
    }

    public function search(Request $request){
        $query = Video::with('videoCategory');

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
