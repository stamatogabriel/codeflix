<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;


    public function testList()
    {
        factory(Category::class, 1)->create();

        $categories = Category::all();
        $this->assertCount(1, $categories);

        $categoryKeys = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ], $categoryKeys);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'teste'
        ]);
        $category->refresh();

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $this->assertNotFalse(preg_match($UUIDv4, $category->id));
        $this->assertEquals(36, strlen($category->id));
        $this->assertEquals('teste', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue((bool)$category->is_active);

        $category = Category::create([
            'name' => 'teste1',
            'description' => null
        ]);
        $category->refresh();

        $this->assertNull($category->description);

        $category = Category::create([
            'name' => 'teste1',
            'description' => 'test description'
        ]);
        $category->refresh();

        $this->assertEquals('test description', $category->description);

        $category = Category::create([
            'name' => 'teste1',
            'is_active' => false,
        ]);
        $category->refresh();

        $this->assertFalse((bool)$category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'teste description',
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'teste_name_update',
            'description' => 'teste_description_update',
            'is_active' => true
        ];

        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $category = factory(Category::class)->create([
            'description' => 'teste description',
        ])->first();

        $category->delete();
        $this->assertTrue($category->deleted_at != null);
        $this->assertNull(Category::find($category->id));
    }
}