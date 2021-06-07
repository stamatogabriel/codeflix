<?php

namespace Tests\Unit\Models;

use App\Models\Video;
use PHPUnit\Framework\TestCase;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class VideoTest extends TestCase
{
    private $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = new Video();
    }

    public function testFillable()
    {
        $fillable = ['title', 'description', 'year_launched', 'opened', 'rating', 'duration'];
        $this->assertEquals(
            $fillable,
            $this->video->getFillable()
        );
    }

    public function testIfUseTraits() 
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $videoTraits = array_keys(class_uses(video::class));
        $this->assertEquals($traits, $videoTraits);
    }

    public function testCasts()
  {
        $casts = ['id' => 'string', 'opened' => 'boolean', 'year_launched' => 'boolean', 'duration' => 'integer'];
        $this->assertEquals(
            $casts,
            $this->video->getCasts()
        );
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->video->incrementing);
    }

    public function testDates()
    {
        $dates = ["created_at", "updated_at", "deleted_at"];
        $videoDates = $this->video->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $videoDates);
        };
     
        $this->assertCount(count($dates), $videoDates);
    }
}