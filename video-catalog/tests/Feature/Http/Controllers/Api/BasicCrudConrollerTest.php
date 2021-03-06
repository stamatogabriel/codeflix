<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\Stubs\Controllers\CategoryControllerStub;
use Tests\Stubs\Models\CategoryStub;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

class BasicCrudControllerTest extends TestCase
{
    private $controller;

    protected function setUp(): void{
      parent::setUp();
      CategoryStub::dropTable();
      CategoryStub::createTable();
      $this->controller = new CategoryControllerStub();
    }

    protected function tearDown(): void
    {
      CategoryStub::dropTable();
      parent::tearDown();
    }

    public function testIndex()
    {
      $category = CategoryStub::create(['name' => 'test Name', 'description' => 'test description']);

      $result = $this->controller->index()->toArray();
      $this->assertEquals([$category->toArray()], $result);
    }

    public function testInvalidationDataStore()
    {
      $this->expectException(ValidationException::class);

      $request = \Mockery::mock(Request::class);
      $request
        ->shouldReceive('all')
        ->once()
        ->andReturn(['name' => '']);
      
      $this->controller->store($request);
    }

    public function testStore()
    {
      $request = \Mockery::mock(Request::class);
      $request
        ->shouldReceive('all')
        ->once()
        ->andReturn(['name' => 'test name', 'description' => 'test  description']);
      $obj = $this->controller->store($request)->toArray();
     $this->assertEquals(
        CategoryStub::find(1)->toArray(),
        $obj
      );
    }

    public function testFindOrFailFetchModel()
    {
      $category = CategoryStub::create(['name' => 'test Name', 'description' => 'test description']);
      
      $reflectionClass = new ReflectionClass(BasicCrudController::class);
      $reflectionMethod = $reflectionClass->getMethod('findOrFail');
      $reflectionMethod->setAccessible(true);

      $result = $reflectionMethod->invokeArgs($this->controller, [$category->id]);
      $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function testFindOrFailThrowExceptionWhenIdInvalid()
    {
      $this->expectException(ModelNotFoundException::class);
            
      $reflectionClass = new ReflectionClass(BasicCrudController::class);
      $reflectionMethod = $reflectionClass->getMethod('findOrFail');
      $reflectionMethod->setAccessible(true);

      $result = $reflectionMethod->invokeArgs($this->controller, [0]);
      $this->assertInstanceOf(CategoryStub::class, $result);
    }

    public function testShow()
    {
      $category = CategoryStub::create(['name' => 'test Name', 'description' => 'test description']);

      $result = $this->controller->show($category->id);
      $this->assertEquals($result->toArray(), $category->toArray());
    }

    public function testUpdate()
    {
      $category = CategoryStub::create(['name' => 'test Name', 'description' => 'test description']);

      $request = \Mockery::mock(Request::class);
      $request
        ->shouldReceive('all')
        ->once()
        ->andReturn(['name' => 'test name', 'description' => 'test2  description']);

      $result = $this->controller->update($request, $category->id);
      $this->assertEquals($result->toArray(), CategoryStub::find(1)->toArray());
    }

    public function testDestroy()
    {
      $category = CategoryStub::create(['name' => 'test Name', 'description' => 'test description']);

      $response = $this->controller->destroy($category->id);
      $this
        ->createTestResponse($response)
        ->assertStatus(204);

        $this->assertCount(0, CategoryStub::all());
    }
}
