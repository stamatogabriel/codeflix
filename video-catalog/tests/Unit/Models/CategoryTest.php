<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use PHPUnit\Framework\TestCase;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Uuid;

class CategoryTest extends TestCase
{
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testFillable()
    {
        $fillable = ['name', 'description', 'is_active'];
        $this->assertEquals(
            $fillable,
            $this->category->getFillable()
        );
    }

    public function testIfUseTraits() 
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEquals($traits, $categoryTraits);
    }

    public function testCasts()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];
        $this->assertEquals(
            $casts,
            $this->category->getCasts()
        );
    }

    public function testIncrementing()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testDates()
    {
        $dates = ["created_at", "updated_at", "deleted_at"];
        $categoryDates = $this->category->getDates();

        foreach($dates as $date) {
            $this->assertContains($date, $categoryDates);
        };
        
        $this->assertCount(count($dates), $categoryDates);
    }
}