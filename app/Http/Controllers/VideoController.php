<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Http\File;
use \InterventionImage;
use Illuminate\Support\Facades\Storage;

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

    public function store(Video $video){
        $input = request()->validate([
            'title' => 'required|max:255',
            'about' => 'required',
            'status' => 'required',
            'thumbnail_name' => 'max:255',
            'video' => 'required|max:2048',
            'video_name' => 'required',
            'video_time' => 'required|max:86400',
            'category' => 'required',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        $video->status = $input['status'];

        $video->video_name = $input['video_name'];
        $video->video_time = $input['video_time'];
        $this->uploadVideoFile($video, $input['video']);

        //サムネイルが設定されている場合
        if(request('thumbnail') !== null) {
            $video->thumbnail_name = $input['thumbnail_name'];
            $this->uploadThumbnailFile($video, request('thumbnail'));
        }

        $video->save();
        $video->videoCategories()->attach(request('category'));

        return $video;
    }

    public function show(Video $video){
        return Video::with('videoCategories:id')->find($video->id);
    }

    public function update(Video $video){
        $input = request()->validate([
            'title' => 'required|max:255',
            'about' => 'required',
            'status' => 'required',
            'thumbnail_name' => 'max:255',
            'video_name' => 'required',
            'video_time' => 'required|max:86400',
            'category' => 'required',
        ]);

        $video->title = $input['title'];
        $video->about = $input['about'];
        $video->status = $input['status'];
        $video->video_name = $input['video_name'];
        $video->video_time = $input['video_time'];

        //サムネイル更新の場合
        if(request('thumbnail')){
            //前データを削除
            if($video->thumbnail){
                $this->deleteThumbnailFile($video->thumbnail);
            }

            $input_t = request()->validate([
                'thumbnail' => 'required',
            ]);

            $this->uploadThumbnailFile($video, $input_t['thumbnail']);
            $video->thumbnail_name = $input['thumbnail_name'];
        }

        //サムネイル削除の場合
        if(request('thumbnail') === null) {
            if($video->thumbnail){
                $this->deleteThumbnailFile($video->thumbnail);
                $video->thumbnail = null;
                $video->thumbnail_name = null;
            }
        }

        if(request('video')){
            //前データを削除
            if($video->video){
                $this->deleteVideoFile($video->video);
            }
            
            $input_t = request()->validate([
                'video' => 'max:2048',
            ]);

            $this->uploadVideoFile($video, $input_t['video']);
        }

        $video->save();
        $video->videoCategories()->sync(request('category'));

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

    private function uploadThumbnailFile($video, $input_thumbnail){
        //ローカルに保存(共通)
        $video->thumbnail = $input_thumbnail->store('thumbnails');
        $this->resizeThumbnail($video->thumbnail);

        if($this->pro_env){
            //ローカルに保存したリサイズ済のサムネイルを取得し、s3にアップロード
            $thumbnail_file_name = str_replace($this->thumbnail_url, '', $video->thumbnail);
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
        $thumbnail_file_name = str_replace($this->thumbnail_url, '', $name);
        InterventionImage::make($this->thumbnail_file_path.$thumbnail_file_name)->resize(800, 500)->save();
    }

    public function search(Request $request){
        $query = Video::with('videoCategories');

        $data = $request->all();
        $search = $data['search'];
        $sort = $data['sort'];

        //検索
        $title = $search['title'];
        $categories = $search['categories'];
        $created_at_start = $search['created_at_start'];
        $created_at_end = $search['created_at_end'];

        if($title){
            $this->searchWord($title, 'title', $query);
        }
        if($categories){
            foreach($categories as $category){
                $query->whereHas('videoCategories', function($q) use ($category){
                    $q->where('id', $category['id']);
                });
            }
        }
        if($created_at_start){
            $query->whereDate('created_at', '>=', $created_at_start)->get();
        }
        if($created_at_end){
            $query->whereDate('created_at', '<=', $created_at_end)->get();
        }

        //ソート
        $select = $sort['select'];
        if($select === 'created_at_desc'){
            $query->orderBy('created_at', 'desc');
        }
        if($select === 'created_at_asc'){
            $query->orderBy('created_at', 'asc');
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
        $video = Video::with('videoCategories')->find($video->id);
        $recommends = $this->getRecommend($video);
        return ['video' => $video, 'recommends' => $recommends];
    }

    private function getRecommend(Video $video){
        //ビデオカテゴリーを取得
        $video_categories = $video->videoCategories()->get();
        if($video_categories->count() == 0) return null;

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
        $file_name = request('file_name');
        $file_path = $file_name;
        $mimeType = Storage::mimeType($file_path);
        $headers = [['Content-Type' => $mimeType]];
        
        return Storage::download($file_path, $file_name, $headers);
    }
}
