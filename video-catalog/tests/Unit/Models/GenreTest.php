<?php

namespace Tests\Unit\Models;

use App\Models\Genre;
use PHPUnit\Framework\TestCase;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class GenreTest extends TestCase
{
    private $genre;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }

    public function testFillable()
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals(
            $fillable,
            $this->genre->getFillable()
        );
    }

    public function testIfUseTraits() 
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals(
            $casts,
            $this->genre->getCasts()
        );
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    public function testDates()
    {
        $dates = ["created_at", "updated_at", "deleted_at"];
        $genreDates = $this->genre->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $genreDates);
        };
        
        $this->assertCount(count($dates), $genreDates);
    }
}