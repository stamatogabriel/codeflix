<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;

class VideoController extends BasicCrudController
{

    public function __construct()
    {
        $this->rules = [
            'title' => 'required|max:255', 
            'description' => 'required', 
            'year_launched'=> 'required|date_format:Y', 
            'opened' => 'boolean|required',
            'rating' => 'required', 
            'duration' => 'required|integer'
        ];
    }


    protected function model(){
        return Video::class;
    }

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
