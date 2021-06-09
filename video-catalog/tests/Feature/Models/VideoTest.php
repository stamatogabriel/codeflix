<?php

namespace Tests\Feature\Models;

use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class VideoTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {
        factory(Video::class, 1)->create();

        $video = Video::all();
        $this->assertCount(1, $video);

        $videoKeys = array_keys($video->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'title', 'description', 'year_launched', 'opened', 'rating', 'duration', 'created_at', 'updated_at', 'deleted_at'
        ], $videoKeys);
    }

    public function testCreate()
    {
        $video = Video::create([
            'title' => 'teste', 
            'description' => 'descrição teste', 
            'year_launched' => 2020, 
            'opened' => true, 
            'rating' => 'L', 
            'duration' => 15
        ]);
        $video->refresh();

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $this->assertEquals(36, strlen($video->id));
        $this->assertNotFalse(preg_match($UUIDv4, $video->id));
        $this->assertEquals('teste', $video->title);
        $this->assertEquals('descrição teste', $video->description);
        $this->assertEquals(2020, $video->year_launched);
        $this->assertEquals(true, $video->opened);
        $this->assertEquals('L', $video->rating);
        $this->assertEquals(15, $video->duration);
    }

    public function testUpdate()
    {
        $video = factory(Video::class)->create([
            'title' => 'teste', 
            'description' => 'descrição teste', 
            'year_launched' => 2020, 
            'opened' => true, 
            'rating' => 'L', 
            'duration' => 15
        ])->first();

        $data = [
            'title' => 'teste_name_update',
            'opened' => false
        ];

        $video->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $video->{$key});
        }
    }

    public function testDelete()
    {
        $video = factory(Video::class)->create([
            'title' => 'teste', 
            'description' => 'descrição teste', 
            'year_launched' => 2020, 
            'opened' => true, 
            'rating' => 'L', 
            'duration' => 15,
        ])->first();

        $video->delete();
        $this->assertTrue($video->deleted_at != null);
        $this->assertNull(Video::find($video->id));
    }
}