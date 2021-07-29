<?php

namespace Appbakkers\Ethereum;

use Web3\Contract;
use Web3\Web3;

class EchtFitTokenContract implements TokenContract
{
    public $contract;

    public function __construct()
    {
        $web3 = new Web3(config('ethereum.host'));

        $this->contract = new Contract($web3->provider, config('contract.abi'));
    }

    public function balanceOf($address): int
    {
        $b = 0;

        $this->contract
            ->at(config('contract.address'))
            ->call('balanceOf', $address, function ($err, $transaction) use (&$b) {
                $b = $transaction[0]->value;
            });

        return $b;
    }

    public function allowance($owner, $spender): int
    {
        $b = 0;

        $this->contract
            ->at(config('contract.address'))
            ->call('allowance', $owner, $spender, function ($err, $transaction) use (&$b) {
                $b = $transaction[0]->value;
            });

        return $b;
    }

    public function transferFrom($sender, $recipient, $amount): bool
    {
        $errors = false;

        $this->contract
            ->at(config('contract.address'))
            ->send('transferFrom', $sender, $recipient, $amount, function ($err, $transaction) use (&$errors) {
                if ($err == null) {
                    $errors = false;
                } else {
                    dump($err);
                }
            });

        return !$errors;
    }


}