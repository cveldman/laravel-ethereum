<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/28/21
 * Time: 12:45 PM
 */

namespace Appbakkers\Ethereum\Traits;

use Web3p\EthereumTx\Transaction;
use Web3\Web3;

trait CanSendTransactions
{

    /**
     * @param $address
     * @return string
     */
    public function getNonce($address) : string {

        $web3 = new Web3(config('ethereum.host'));

        // Get Nonce for chain
        $web3->getEth()->getTransactionCount($address, 'pending', function($err, $response) use(&$nonce){
            if ($err != null)
                dd($err);

            $nonce = '0x'.$response->toHex();

        });

        return $nonce;
    }


    /**
     * @param string $from
     * @param string $to
     * @param string $nonce
     * @param $data
     * @return string
     */
    public function signDataTransaction(string $from, string $to, string $nonce, $data) {
        // Create raw transaction
        $transaction = new Transaction([
            'from' => $from,
            'to' => $to,
            'value'=> '0x0',
            'nonce' => $nonce,
            'gas' => 2100000,
            'gasPrice' => '0x0',
            'chainId' => config('ethereum.chain_id'),
            'data' => $data
        ]);

        // Sign transaction using private key
        return '0x'.$transaction->sign(config('ethereum.private_key'));
    }
}