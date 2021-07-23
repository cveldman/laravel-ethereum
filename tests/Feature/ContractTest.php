<?php

namespace Appbakkers\Ethereum\Tests\Feature;

use Appbakkers\Ethereum\EchtFitTokenContract;
use Appbakkers\Ethereum\Tests\TestCase;

class ContractTest extends TestCase
{
    /** @test */
    public function example()
    {
        $contract = new EchtFitTokenContract();

        //dd($contract->totalSupply());
    }
}
