<?php

namespace Appbakkers\Ethereum\Tests\Feature;

use Appbakkers\Ethereum\Tests\Fixtures\User;
use Appbakkers\Ethereum\Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /** @test */
    public function example()
    {
        $user = User::create([
            'name' => 'Appbakkers',
            'email' => 'support@appbakkers.nl',
            'password' => Hash::make('password'),
            'address' => '0x966a7327D0853aE5200a5051820ba28B07e39DE3'
        ]);

        $balance = $user->balance();

        dump('BALANCE: ' . $balance);

        $allowance = $user->allowance();

        dump('ALLOWANCE: ' . $allowance);

        $user->transfer(1);

        dump('TRANSFER: ' . $balance);

        $this->assertEquals($balance - 1, $user->balance());

        dump('TRANSFER: ' . $balance);

    }
}
