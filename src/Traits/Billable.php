<?php

namespace Appbakkers\Ethereum\Traits;

use Appbakkers\Ethereum\EchtFitTokenContract;

trait Billable
{
    public function balance(): int
    {
        return (new EchtFitTokenContract())->balanceOf($this->address);
    }

    public function allowance(): int
    {
        return (new EchtFitTokenContract())->allowance($this->address, config('contract.owner'));
    }

    public function transfer(int $tokens): void
    {
        (new EchtFitTokenContract())->transferFrom($this->address, config('contract.owner'), $tokens);
    }

    public function mint(int $tokens): void
    {
        (new EchtFitTokenContract())->mintCoins($this->address, $tokens);
    }
}
