<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class VideoStub extends Model
{
    protected $table = 'video_stubs';
    protected $fillable = ['title', 'description', 'year_launched', 'opened', 'rating', 'duration'];

    public static function createTable(){
        \Schema::create('video_stubs', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('description');
            $table->smallInteger('year_launched');
            $table->boolean('opened')->default(false); // se é lançamento ou não
            $table->string('rating', 3); // classificação etária
            $table->smallInteger('duration');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public static function dropTable() {
        \Schema::dropIfExists('video_stubs');
    }
}
