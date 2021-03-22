<?php

namespace App\Http\Controllers;

use App\Models\VideoEvaluation;
use App\Models\Video;

class VideoEvaluationController extends Controller
{
    public function getEvaluation(Video $video){
        $evaluations = VideoEvaluation::find($video)->all();
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
}
