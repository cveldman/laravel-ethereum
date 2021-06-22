<?php

namespace Appbakkers\Ethereum\Tests\Unit;

use Appbakkers\Ethereum\Tests\TestCase;
use Web3\Contract;
use Web3\Eth;
use Web3\Web3;

class ExampleTest extends TestCase
{
    /** @test */
    public function example()
    {
        $eth = new Eth('http://localhost:7545');

        echo 'Eth Get Account and Balance' . PHP_EOL;
        $eth->accounts(function ($err, $accounts) use ($eth) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            foreach ($accounts as $account) {
                echo 'Account: ' . $account . PHP_EOL;

                $eth->getBalance($account, function ($err, $balance) {
                    if ($err !== null) {
                        echo 'Error: ' . $err->getMessage();
                        return;
                    }
                    echo 'Balance: ' . $balance . PHP_EOL;
                });
            }
        });

        $this->assertTrue(true);
    }

    public function aatest()
    {
        $eth = new Eth('http://localhost:7545');
        $eth->getBalance('0x0745ef8A4eF0E001af0336e805c97D475Ab22Df2', function ($err, $balance) {
            if ($err !== null) {
                echo 'Error: ' . $err->getMessage();
                return;
            }
            echo 'Balance: ' . $balance->toString() . PHP_EOL;
        });
    }


    public function test()
    {
        $abi = config('ethereum.contract.abi');

        $web3 = new Web3('http://localhost:7545');

        $contract = new Contract($web3->provider, $abi);

        $contractAddress = '0xf007d2487E472D20272759F9203753059577d932';
        $address = '0x0745ef8A4eF0E001af0336e805c97D475Ab22Df2';

        $contract->at($contractAddress)->call('balanceOf', $address, function ($err, $transaction) {
            return $transaction[0];
            // dd($transaction);
        });
    }
}
