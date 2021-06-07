<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastMemberTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {
        factory(CastMember::class, 1)->create();

        $castMember = CastMember::all();
        $this->assertCount(1, $castMember);

        $castMemberKeys = array_keys($castMember->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'type', 'created_at', 'updated_at', 'deleted_at'
        ], $castMemberKeys);
    }

    public function testCreate()
    {
        $castMember = CastMember::create([
            'name' => 'teste'
        ]);
        $castMember->refresh();

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        $this->assertEquals(36, strlen($castMember->id));
        $this->assertNotFalse(preg_match($UUIDv4, $castMember->id));
        $this->assertEquals('teste', $castMember->name);
        $this->assertEquals($castMember->type, 2);

        $castMember = CastMember::create([
            'name' => 'teste1',
            'type' => 1,
        ]);
        $castMember->refresh();

        $this->assertEquals($castMember->type, 1);
    }

    public function testUpdate()
    {
        $castMember = factory(CastMember::class)->create([
            'type' => 1
        ])->first();

        $data = [
            'name' => 'teste_name_update',
            'type' => 2
        ];

        $castMember->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }
    }

    public function testDelete()
    {
        $castMember = factory(CastMember::class)->create([
            'name' => 'teste name',
        ])->first();

        $castMember->delete();
        $this->assertTrue($castMember->deleted_at != null);
        $this->assertNull(CastMember::find($castMember->id));
    }
}