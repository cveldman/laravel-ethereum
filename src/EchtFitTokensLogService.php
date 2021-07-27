<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/26/21
 * Time: 10:46 AM
 */

namespace Appbakkers\Ethereum;

use Illuminate\Support\Facades\Log;
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
    public function getAllLogs($topics)
    {
        // Create call params
        $params = [
            'fromBlock' => 'earliest',
            'toBlock' => 'latest',
            'address' => config('contract.address'),
        ];

        return $this->getLogs($params, $topics);
    }

    /**
     * Call eth_getLogs with params and topics
     * @param $params array
     * @param $topics array
     * @param callable $callback
     */
    public function getLogs($params, $topics){

        // add topics if supplied
        if(count($topics) > 0)
            $params['topics'] = $topics;

        $logs = [];
        $this->web3->getEth()->getLogs(
            $params,
            function($err, $response) use(&$logs){

            if($err != null) {
                Log::error($err);
                dd($err);
            }

            // Convert to human readable objects
            if($response != null)
                foreach ($response as $log){
                    $logs[] = new EventLog($log);
                }
        });
        return $logs;
    }

    /**
     * Get all logs of a certain wallet
     * @param $address
     * @param callable $callback
     */
    public function getAllLogsForWallet($address) {

        // Padd bytes to 32
        $bytesAddress = Web3Utils::padBytes($address, 32);

        // Create topics param
        $topics = [
            null,
            $bytesAddress
        ];

        // Call eth_getLogs
        return $this->getAllLogs($topics);
    }
}