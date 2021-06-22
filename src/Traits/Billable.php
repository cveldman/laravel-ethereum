<?php

namespace Appbakkers\Ethereum\Traits;

use Web3\Contract;
use Web3\Web3;

trait Billable {
    public function balance() {
        $abi = config('ethereum');

        $web3 = new Web3('http://localhost:7545');
        dd($abi);
        $contract = new Contract($web3->provider, $abi);

        $contractAddress = '0xf007d2487E472D20272759F9203753059577d932';
        $address = '0x0745ef8A4eF0E001af0336e805c97D475Ab22Df2';

        $contract->at($contractAddress)->call('balanceOf', $address, function ($err, $transaction) {
            return $transaction[0];
        });
    }

    public function transfer() {

    }

    public function refund() {

    }
}
