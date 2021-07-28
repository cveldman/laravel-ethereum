<?php

namespace Appbakkers\Ethereum;

use Appbakkers\Ethereum\Helpers\Web3Utils;
use Appbakkers\Ethereum\Traits\CanSendTransactions;
use Appbakkers\Ethereum\Traits\Web3Unlockable;
use Web3\Contract;
use Web3\Web3;


class EchtFitTokenContract implements TokenContract
{

    use CanSendTransactions;

    /**
     * Echttoken DAP Instance
     * @var Contract
     */
    public $contract;

    /**
     * Web3 instance
     * @var Web3
     */
    public $web3;


    public function __construct()
    {
        $this->web3 = new Web3(config('ethereum.host'));

        $this->contract = new Contract($this->web3->provider, config('contract.abi'));
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
            ->call('transferFrom', $sender, $recipient, $amount, function ($err, $transaction) use (&$errors) {
                if ($err == null) {
                    $errors = false;
                } else {
                    dump($err);
                }
            });

        return !$errors;
    }

    /**
     * Give specified ammount of tokens to address
     * @param $recipient
     * @param $amount
     * @return string
     */
    public function mintCoins(string $recipient, int $amount): string {

        // Get Nonce for chain
        $nonce = $this->getNonce(config('contract.owner'));

        // Convert method and parameters to Hash string
        $data = '0x'. $this->contract->at(config('contract.address'))->getData('mintCoins', $recipient, $amount);

        $signedTransaction = $this->signDataTransaction(config('contract.owner'), config('contract.address'), $nonce, $data);

        // transaction Hash to return
        $transactionHash = '';

        // Send raw transaction to chain
        $this->web3->getEth()->sendRawTransaction($signedTransaction, function($err, $tx) use(&$transactionHash){
            if($err != null)
                dd($err);

            $transactionHash = $tx;
        });

        return $transactionHash;
    }

}