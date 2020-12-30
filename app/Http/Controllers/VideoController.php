<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\VideoComment;
use \InterventionImage;

class VideoController extends Controller
{
    private $thumbnail_url;
    private $thumbnail_file_path;
    private $video_url;
    private $video_file_path;

    public function __construct(){
        $this->thumbnail_url = env('APP_URL').'/storage/thumbnails/';
        $this->thumbnail_file_path = storage_path('app/public/thumbnails/');
        $this->video_url = env('APP_URL').'/storage/videos/';
        $this->video_file_path = storage_path('app/public/videos/');
    }

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
        $this->resizeThumbnail($video->thumbnail);
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
            $this->resizeThumbnail($video->thumbnail);
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
        //ファイル名を取得
        $thumbnail_file_name = str_replace($this->thumbnail_url, '', $thumbnail);

        //ダミーデータを削除しない処理
        $sample_thumbnail = 'A_thumbnail_sample.jpeg';
        if($thumbnail_file_name === $sample_thumbnail){
            return;
        }

        //ファイル削除
        if(is_file($this->thumbnail_file_path.$thumbnail_file_name)){
            unlink($this->thumbnail_file_path.$thumbnail_file_name);
        }
    }

    private function deleteVideoFile($video){
        //ファイル名を取得
        $video_file_name = str_replace($this->video_url, '', $video);

        //ダミーデータを削除しない処理
        $sample_video = 'A_video_sample.qt';
        if($video_file_name === $sample_video){
            return;
        }

        //ファイル削除
        if(is_file($this->video_file_path.$video_file_name)){
            unlink($this->video_file_path.$video_file_name);
        }
    }

    private function resizeThumbnail($name){
        $thumbnail_file_name = str_replace($this->thumbnail_url, '', $name);
        InterventionImage::make($this->thumbnail_file_path.$thumbnail_file_name)->resize(800, 500)->save();
    }

    public function search(Request $request){
        $query = Video::with('videoCategory');

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
                $query->whereHas('videoCategory', function($q) use ($category){
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
        $video = Video::with('videoCategory')->find($video->id);
        $comments = VideoComment::with(['user', 'reVideoComments.user'])
        ->where('video_id', $video->id)
        ->paginate(request('per_page'));
        $recommends = $this->getRecommend($video);

        return ['video' => $video, 'comments' => $comments, 'recommends' => $recommends];
    }

    private function getRecommend(Video $video){
        //ビデオカテゴリーを取得
        $video_categories = $video->videoCategory()->get();
        foreach($video_categories as $video_category){
            $video_categories_id[] = $video_category->id;
        }
        
        //一致するカテゴリーの数の配列を作成
        $r_videos = Video::with('videoCategory')->where('id', '!=', $video->id)->get();
        $same_num = [];
        foreach($r_videos as $r_video){
            $r_categories = $r_video->videoCategory()->get();
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
}
