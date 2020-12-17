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
            //前データを削除
            if($video->thumbnail){
                $this->deleteThumbnailFile($video->thumbnail);
            }
            $video->thumbnail = request('thumbnail')->store('thumbnails');
        }
        if(request('video')){
            //前データを削除
            if($video->video){
                $this->deleteVideoFile($video->video);
            }
            $video->video = request('video')->store('videos');
        }
        $video->thumbnail_name = $input['thumbnail_name'];
        $video->video_name = $input['video_name'];
        $video->save();
        $video->videoCategory()->sync(request('category'));

        return $video;
    }

    public function destroy(Video $video){
        if($video->thumbnail){
            $this->deleteThumbnailFile($video->thumbnail);
        }
        if($video->video){
            $this->deleteVideoFile($video->video);
        }

        $video->delete();
        return $video;
    }

    private function deleteThumbnailFile($thumbnail){
        $thumbnail_url = env('APP_URL').'/storage/thumbnails/';
        $thumbnail_file_path = storage_path('app/public/thumbnails/');

        //ファイル名を取得
        $thumbnail_file_name = str_replace($thumbnail_url, '', $thumbnail);

        //ダミーデータを削除しない処理
        $sample_thumbnail = 'A_thumbnail_sample.jpeg';
        if($thumbnail_file_name === $sample_thumbnail){
            return;
        }

        //ファイル削除
        if(is_file($thumbnail_file_path.$thumbnail_file_name)){
            unlink($thumbnail_file_path.$thumbnail_file_name);
        }
    }

    private function deleteVideoFile($video){
        $video_url = env('APP_URL').'/storage/videos/';
        $video_file_path = storage_path('app/public/videos/');

        //ファイル名を取得
        $video_file_name = str_replace($video_url, '', $video);

        //ダミーデータを削除しない処理
        $sample_video = 'A_video_sample.qt';
        if($video_file_name === $sample_video){
            return;
        }

        //ファイル削除
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
        $created_at_start = $search['created_at_start'];
        $created_at_end = $search['created_at_end'];
        if($title){
            $this->searchWord($title, 'title', $query);
        }
        if($created_at_start){
            $query->whereDate('created_at', '>=', $created_at_start)->get();
        }
        if($created_at_end){
            $query->whereDate('created_at', '<=', $created_at_end)->get();
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
