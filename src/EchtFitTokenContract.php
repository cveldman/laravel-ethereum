<?php

namespace Appbakkers\Ethereum;

use Appbakkers\Ethereum\Exceptions\InsufficientAllowanceException;
use Appbakkers\Ethereum\Exceptions\TransactionFailedException;
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


    /**
     * @param $address
     * @return int
     * @throws TransactionFailedException if operation fails
     */
    public function balanceOf($address): int
    {
        $b = 0;

        $this->contract
            ->at(config('contract.address'))
            ->call('balanceOf', $address, function ($err, $transaction) use (&$b) {
                if($err != null)
                    Throw new TransactionFailedException($err);

                $b = $transaction[0]->value;
            });

        return $b;
    }

    /**
     * @param $owner
     * @param $spender
     * @return int
     * @throws TransactionFailedException if operation fails
     */
    public function allowance($owner, $spender): int
    {
        $b = 0;

        $this->contract
            ->at(config('contract.address'))
            ->call('allowance', $owner, $spender, function ($err, $transaction) use (&$b) {
                if($err != null)
                    Throw new TransactionFailedException($err);

                $b = $transaction[0]->value;
            });

        return $b;
    }

    /**
     * @param $sender
     * @param $recipient
     * @param $amount
     * @return string
     * @throws InsufficientAllowanceException
     */
    public function transferFrom($sender, $recipient, $amount): string
    {
        // check allowance
        $allowance = $this->allowance($sender, config('contract.owner'));;
        if($amount > $allowance)
            throw new InsufficientAllowanceException();

        // Create transaction
        $transactionHash = '';
        $this->contract
            ->at(config('contract.address'))
            ->send('transferFrom', $sender, $recipient, $amount, function ($err, $transaction) use(&$transactionHash) {
                if($err != null)
                    Throw new TransactionFailedException($err);

                $transactionHash = $transaction;
            });

        return $transactionHash;
    }

    /**
     * Give specified ammount of tokens to address
     * @param string $recipient
     * @param int $amount
     * @return string
     * @throws TransactionFailedException if operation fails
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
                Throw new TransactionFailedException($err);

            $transactionHash = $tx;
        });

        return $transactionHash;
    }

}