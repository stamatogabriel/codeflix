<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;

class VideoController extends BasicCrudController
{

    private $rules = [
        'title' => 'required|max:255', 
        'description' => 'required', 
        'year_launched'=> 'integer|required', 
        'opened' => 'boolean', 
        'rating' => 'required|max:3', 
        'duration' => 'required|integer'
    ];

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
