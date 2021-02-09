<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'about' => 'required',
            'status' => 'required',
            'thumbnail' => '',
            'thumbnail_name' => 'max:255',
            'video' => 'required|max:2048',
            'video_name' => 'required',
            'video_time' => 'required|integer|max:86399',
            'category' => 'required',
        ];
    }
}
