<?php

namespace Tests\Feature;

use App\Contract\UserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    public function testUserTest()
    {
        $repository = \Mockery::mock(UserRepositoryInterface::class);
        $repository->shouldReceive('all')->once()->andReturn(['学院君']);
        $this->instance(UserRepositoryInterface::class, $repository);
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertViewHas('users', ['学院君']);
    }
}
