<?php

namespace Appbakkers\Ethereum;

use Appbakkers\Ethereum\Helpers\Web3Utils;
use Appbakkers\Ethereum\Traits\Web3Unlockable;
use Web3\Contract;
use Web3\Web3;
use Web3p\EthereumTx\Transaction;

class EchtFitTokenContract implements TokenContract
{

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

    public function mintCoins($recipient, $amount): string {

        $nonce = null;

        $this->web3->getEth()->getTransactionCount(config('contract.owner'), 'pending', function($err, $response) use(&$nonce){
            if ($err != null)
                dd($err);

            echo $response->value;
            $nonce = $response->value;
        });

        $data = '0x'. $this->contract->at(config('contract.address'))->getData('mintCoins', $recipient, $amount);
        $txParams = [
            'from' => config('contract.owner'),
            'to' => config('contract.address'),
            'value'=> '0x0',
            'nonce' => $nonce,
            'gas' => '0x33450',
            'gasPrice' => '0x0',
            'chainId' => config('ethereum.chain_id'),
            'data' => $data
        ];

        $transaction = new Transaction($txParams);
        $signedTransaction = $transaction->sign(config('ethereum.private_key'));

        $transactionHash = '';
        $this->web3->getEth()->sendRawTransaction('0x'.$signedTransaction, function($err, $tx) use(&$transactionHash){
            if($err != null)
                dd($err);
            $transactionHash = $tx;
        });

        return $transactionHash;
    }

}