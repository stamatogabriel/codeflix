<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {
        factory(Genre::class, 1)->create();

        $genres = Genre::all();
        $this->assertCount(1, $genres);

        $genreKeys = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ], $genreKeys);
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'teste'
        ]);
        $genre->refresh();

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $this->assertEquals(36, strlen($genre->id));
        $this->assertNotFalse(preg_match($UUIDv4, $genre->id));
        $this->assertEquals('teste', $genre->name);
        $this->assertTrue((bool)$genre->is_active);

        $genre = Genre::create([
            'name' => 'teste1',
            'is_active' => false,
        ]);
        $genre->refresh();

        $this->assertFalse((bool)$genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'is_active' => false
        ])->first();

        $data = [
            'name' => 'teste_name_update',
            'is_active' => true
        ];

        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genre = factory(Genre::class)->create([
            'name' => 'teste name',
        ])->first();

        $genre->delete();
        $this->assertTrue($genre->deleted_at != null);
        $this->assertNull(Genre::find($genre->id));
    }
}