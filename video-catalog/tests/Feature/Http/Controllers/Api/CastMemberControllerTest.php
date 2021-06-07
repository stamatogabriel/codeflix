<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    public function testIndex()
    {
        $castMember = factory(CastMember::class)->create();
        $response = $this->get(route('cast_members.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$castMember->toArray()]);
    }

    public function testShow()
    {
        $castMember = factory(CastMember::class)->create();
        $response = $this->get(route('cast_members.show', ['cast_member' => $castMember->id]));

        $response
            ->assertStatus(200)
            ->assertJson($castMember->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertInvalidationInStoreAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);

        $castMember = factory(CastMember::class)->create();
        $response = $this->json('PUT', route('cast_members.update', ['cast_member' => $castMember->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT', route('cast_members.update', ['cast_member' => $castMember->id]), [
            'name' => str_repeat('a', 256),
            'type' => 2
        ]);
        $this->assertMaxInvalidationRequired($response);
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
    }

    protected function assertMaxInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    }

    public function testStore()
    {
        $data = [
            'name' => 'test'
        ];
        $response = $this->assertStore($data, $data + ['type' => 2, 'deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testUpdate()
    {
        $this->castMember = factory(CastMember::class)->create([
            'type' => 1
        ]);

        $data = [
            'name' => 'test',
            'type' => 2
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);
    }

    public function testDestroy()
    {
        $castMember = factory(CastMember::class)->create();
        $response = $this->json('DELETE', route('cast_members.destroy', ['cast_member' => $castMember->id]));

        $response->assertStatus(204);

        $this->assertNull(CastMember::find($castMember->id));
        $this->assertNotNull(CastMember::withTrashed()->find($castMember->id));
    }

    protected function routeStore() {
        return route('cast_members.store');
    }

    protected function routeUpdate()
    {
        return route('cast_members.update', ['cast_member' => $this->castMember->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
