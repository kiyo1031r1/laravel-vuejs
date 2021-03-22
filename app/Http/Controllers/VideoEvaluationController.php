<?php

namespace App\Http\Controllers;

use App\Models\VideoEvaluation;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoEvaluationController extends Controller
{
    public function getEvaluation(Video $video){
        $evaluations = $video->videoEvaluations;
        if(count($evaluations) > 0){
            $sum = 0;
            foreach($evaluations as $evaluate){
                $sum += $evaluate->evaluation;
            }
            $evaluation = $sum / count($evaluations);
        }
        else{
            $evaluation = 0;
        }

        return $evaluation;
    }

    public function evaluate(Video $video, Request $request){
        $input = $request->validate([
            'evaluation' => ['required', 'integer', 'in:1,2,3,4,5'],
            'user_id' => ['required', 'integer', 'unique:video_evaluations']
        ]);

        VideoEvaluation::create([
            'evaluation' => $input['evaluation'],
            'video_id' => $video->id,
            'user_id' => $input['user_id'],
        ]);
    }

    public function update(Video $video, Request $request){
        
    }

    public function isEvaluate(Video $video){
        $user = Auth::user();
        $evaluation = VideoEvaluation::where('video_id', $video->id)
        ->where('user_id', $user->id)->first();

        if($evaluation){
            return ['is_evaluate' => 'true', 'evaluation' => $evaluation->evaluation];
        }
        else{
            return 'false';
        }
    }
}
