<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/26/21
 * Time: 1:21 PM
 */

namespace Appbakkers\Ethereum;

use Appbakkers\Ethereum\Objects\EventLog;
use Web3\Web3;

class EchtFitTransactionService
{
    /**
     * @var Web3 Client
     */
    private $web3;

    /**
     * EchtFitTransactionService constructor.
     */
    public function __construct()
    {
        $this->web3 = new Web3(config('ethereum.host'));
    }

    /**
     * @param string $transactionHash
     * @param callable $callback
     */
    public function getTransactionReceipt(string $transactionHash){

        $receipt = null;
        $this->web3->getEth()->getTransactionReceipt($transactionHash, function($err, $response) use(&$receipt) {

            $decodedLogs = [];

            if($response != null && isset($response->logs) && count($response->logs) > 0) {
                foreach ($response->logs as $log)
                    $decodedLogs[] = new EventLog($log);

                $response->decodedLogs = $decodedLogs;
            }

            $receipt = $response;
        });

        return $receipt;
    }
}