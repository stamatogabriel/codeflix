<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use PHPUnit\Framework\TestCase;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class CastMemberTest extends TestCase
{
    private $castMember;

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    public function testFillable()
    {
        $fillable = ['name', 'type'];
        $this->assertEquals(
            $fillable,
            $this->castMember->getFillable()
        );
    }

    public function testIfUseTraits() 
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $castMemberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $castMemberTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string'];
        $this->assertEquals(
            $casts,
            $this->castMember->getCasts()
        );
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->castMember->incrementing);
    }

    public function testDates()
    {
        $dates = ["created_at", "updated_at", "deleted_at"];
        $castMemberDates = $this->castMember->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $castMemberDates);
        };
        
        $this->assertCount(count($dates), $castMemberDates);
    }
}