<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Http\File;
use \InterventionImage;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;

class VideoController extends Controller
{
    private $thumbnail_url;
    private $thumbnail_file_path;
    private $video_url;
    private $pro_env;

    public function __construct(){
        $this->thumbnail_url = env('APP_URL').'/storage/thumbnails/';
        $this->thumbnail_file_path = storage_path('app/public/thumbnails/');
        $this->video_url = env('APP_URL').'/storage/videos/';
        $this->video_file_path = storage_path('app/public/videos/');
        $this->pro_env = app()->environment('production');
    }

    public function store(StoreVideoRequest $request){
        //入力値を元に一旦作成
        $request = $request->validated();
        $video = Video::create($request);

        //ビデオとサムネイルのファイルパスを加工したものを上書き
        $this->uploadVideoFile($video, $request['video']);
        if(!empty($request['thumbnail'])) {
            $this->uploadThumbnailFile($video, $request['thumbnail']);
        }
        $video->save();
        $video->videoCategories()->attach($request['category']);
    }

    public function show(Video $video){
        return Video::with('videoCategories:id')->find($video->id);
    }

    public function update(UpdateVideoRequest $request, Video $video){
        $request = $request->validated();
        $video->update([
            'title' => $request['title'],
            'about' => $request['about'],
            'status' => $request['status'],
            'thumbnail_name' => $request['thumbnail_name'],
            'video_name' => $request['video_name'],
            'video_time' => $request['video_time'],
        ]);

        //サムネイル更新
        if($request['thumbnail']){
            $this->deleteThumbnailFile($video->thumbnail);
            $this->uploadThumbnailFile($video, $request['thumbnail']);
        }
        //サムネイル削除
        elseif(!$request['thumbnail'] && !$request['thumbnail_name']){
            $this->deleteThumbnailFile($video->thumbnail);
            $video->thumbnail = null;
        }

        //ビデオ更新
        if($request['video']){
            $this->deleteVideoFile($video->video);
            $this->uploadVideoFile($video, $request['video']);
        }

        $video->save();
        $video->videoCategories()->sync($request['category']);
    }

    public function destroy(Video $video){
        if($video->thumbnail){
            $this->deleteThumbnailFile($video->thumbnail);
        }
        if($video->video){
            $this->deleteVideoFile($video->video);
        }

        $video->delete();
    }

    private function uploadThumbnailFile($video, $input_thumbnail){
        //ローカルに保存(共通)
        $video->thumbnail = $input_thumbnail->store('thumbnails');
        $this->resizeThumbnail($video->thumbnail);

        if($this->pro_env){
            //ローカルに保存したリサイズ済のサムネイルを取得し、s3にアップロード
            $thumbnail_file_name = str_replace('thumbnails/', '', $video->thumbnail);
            $local_thumbnail_path = $this->thumbnail_file_path.$thumbnail_file_name;
            $path = Storage::disk('s3')->putFile('thumbnails', new File($local_thumbnail_path), 'public');
            $video->thumbnail = Storage::disk('s3')->url($path);

            //s3に保存後はローカルのサムネイルを削除
            unlink($local_thumbnail_path);
        }
    }

    private function uploadVideoFile($video, $input_video){
        if($this->pro_env){
            $path = Storage::disk('s3')->putFile('videos', $input_video, 'public');
            $video->video = Storage::disk('s3')->url($path);
        }
        else{
            $video->video = $input_video->store('videos');
        }
    }

    private function deleteThumbnailFile($thumbnail){
        //ダミーデータを削除しない処理
        if($thumbnail === '/images/dummyData/dummy_thumbnail.jpeg') return;

        if($this->pro_env){
            $thumbnail_file_name = str_replace(env('AWS_URL'), '', $thumbnail);
            if(Storage::disk('s3')->exists($thumbnail_file_name)){
                Storage::disk('s3')->delete($thumbnail_file_name);
            }
        }
        else{
            $thumbnail_file_name = str_replace($this->thumbnail_url, '', $thumbnail);
            if(Storage::exists('thumbnails/'.$thumbnail_file_name)){
                Storage::delete('thumbnails/'.$thumbnail_file_name);
            }
        }
    }

    private function deleteVideoFile($video){
        //ダミーデータを削除しない処理
        if($video === '/images/dummyData/dummy_video.qt') return;

        if($this->pro_env){
            $video_file_name = str_replace(env('AWS_URL'), '', $video);
            if(Storage::disk('s3')->exists($video_file_name)){
                Storage::disk('s3')->delete($video_file_name);
            }
        }
        else{
            $video_file_name = str_replace($this->video_url, '', $video);
            if(Storage::exists('videos/'.$video_file_name)){
                Storage::delete('videos/'.$video_file_name);
            }
        }
    }

    private function resizeThumbnail($name){
        //本番環境の場合は、保存時に加工しない為
        if($this->pro_env){
            $thumbnail_file_name = str_replace('thumbnails/', '', $name);
        }
        else{
            $thumbnail_file_name = str_replace($this->thumbnail_url, '', $name);
        }
        InterventionImage::make($this->thumbnail_file_path.$thumbnail_file_name)->resize(800, 500)->save();
    }

    public function search(Request $request){
        $query = Video::with('videoCategories');

        //検索
        $search = $request['search'];
        if($search['title']){
            $this->searchWord($search['title'], 'title', $query);
        }
        if($search['categories']){
            foreach($search['categories'] as $category){
                $query->whereHas('videoCategories', function($q) use ($category){
                    $q->where('id', $category['id']);
                });
            }
        }
        if($search['created_at_start']){
            //dateTime型に変換し、日本時刻に変換した値で検索
            $date = new DateTime($search['created_at_start']);
            $date->setTimezone( new DateTimeZone('Asia/Tokyo'))->format(DateTime::ISO8601);
            $query->whereDate('created_at', '>=', $date)->get();
        }
        if($search['created_at_end']){
            $date = new DateTime($search['created_at_end']);
            $date->setTimezone( new DateTimeZone('Asia/Tokyo'))->format(DateTime::ISO8601);
            $query->whereDate('created_at', '<=', $date)->get();
        }

        //ソート
        $sort = $request['sort'];
        if($sort['sort'] === 'created_at_desc'){
            $query->orderBy('created_at', 'desc');
        }
        if($sort['sort'] === 'created_at_asc'){
            $query->orderBy('created_at', 'asc');
        }
        if($sort['sort'] === 'evaluation_desc'){
            $query->orderBy('evaluation', 'desc');
        }
        if($sort['sort'] === 'evaluation_asc'){
            $query->orderBy('evaluation', 'asc');
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

    public function watch(Video $video){
        $video = Video::with('videoCategories')->findOrFail($video->id);
        $recommends = $this->getRecommend($video);
        return ['video' => $video, 'recommends' => $recommends];
    }

    public function exist(Request $request){
        return Video::findOrFail($request['id']);
    }

    private function getRecommend(Video $video){
        //ビデオカテゴリーを取得
        $video_categories = $video->videoCategories()->get();
        if($video_categories->count() == 0) {
            return null;
        }

        foreach($video_categories as $video_category){
            $video_categories_id[] = $video_category->id;
        }
        
        //一致するカテゴリーの数の配列を作成
        $r_videos = Video::with('videoCategories')->where('id', '!=', $video->id)->get();
        $same_num = [];
        foreach($r_videos as $r_video){
            $r_categories = $r_video->videoCategories()->get();
            foreach($r_categories as $r_category){
                $r_video_categories_id[] = $r_category->id;
            }
            $same_category_num = count(array_intersect($video_categories_id, $r_video_categories_id));
            $same_num[$r_video->id] = $same_category_num;
            $r_video_categories_id = [];
        }
        arsort($same_num);

        //上位10個のデータを送る
        $recommends = collect([]);
        $count = 0;
        foreach($same_num as $id => $value){
            if($count > 9) {
                break;
            }
            $recommends->push($r_videos->find($id));
            $count++;
        }

        return $recommends;
    }

    public function download(Video $video){
        return Storage::download(request('file_name'));
    }
}
