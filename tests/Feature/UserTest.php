<?php

namespace Appbakkers\Ethereum\Tests\Feature;

use Appbakkers\Ethereum\Models\User;
use Appbakkers\Ethereum\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function example()
    {
        $user = User::factory()->create();

        $this->assertEquals(1000, $user->balance());

        dd($user);
    }
}
