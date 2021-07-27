<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/26/21
 * Time: 10:46 AM
 */

namespace Appbakkers\Ethereum;

use Web3\Web3;
use Appbakkers\Ethereum\Helpers\Web3Utils;
use Appbakkers\Ethereum\Objects\EventLog;

class EchtFitTokensLogService
{

    /**
     * @var Web3 Client
     */
    private $web3;

    /**
     * EchtFitTokensLogService constructor.
     */
    public function __construct()
    {
        $this->web3 = new Web3(config('ethereum.host'));
    }


    /**
     * Get all Event logs from contract
     * @param $topics Array of topic filters
     * @param callable $callback callback handler
     */
    public function getAllLogs($topics, callable $callback)
    {
        if (is_callable($callback) !== true) {
            throw new \InvalidArgumentException('The last param must be callback function.');
        }

        // Create call params
        $params = [
            'fromBlock' => 'earliest',
            'toBlock' => 'latest',
            'address' => config('contract.address'),
        ];

        $this->getLogs($params, $topics, $callback);
    }

    /**
     * Call eth_getLogs with params and topics
     * @param $params Array
     * @param $topics Array
     * @param callable $callback
     */
    public function getLogs($params, $topics, callable $callback){

        // add topics if supplied
        if(count($topics) > 0)
            $params['topics'] = $topics;

        $this->web3->getEth()->getLogs(
            $params, function($err, $response) use($callback){

            // Decoded logs
            $logs = [];

            // Convert to human readable objects
            if($response != null)
                foreach ($response as $log){
                    $logs[] = new EventLog($log);
                }

            // Call callback
            $callback($err, $logs);
        });
    }

    /**
     * Get all logs of a certain wallet
     * @param $address
     * @param callable $callback
     */
    public function getAllLogsForWallet($address, callable  $callback) {

        // Padd bytes to 32
        $bytesAddress = Web3Utils::padBytes($address, 32);

        // Create topics param
        $topics = [
            null,
            $bytesAddress
        ];

        // Call eth_getLogs
        $this->getAllLogs($topics, $callback);
    }
}