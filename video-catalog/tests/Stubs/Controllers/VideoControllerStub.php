<?php

namespace Tests\Stubs\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\VideoStub;

class VideoControllerStub extends BasicCrudController
{
    protected function model()
    {
        return VideoStub::class;
    }

    private $rules = [
        'title' => 'required|max:255', 
        'description' => 'required', 
        'year_launched'=> 'integer|required', 
        'opened' => 'boolean', 
        'rating' => 'required|max:3', 
        'duration' => 'required|integer'
    ];

    protected function rulesStore()
    {
        return $this->rules;
    }

    protected function rulesUpdate()
    {
        return $this->rules;
    }
}
