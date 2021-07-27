<?php
/**
 * Created by PhpStorm.
 * User: martijndebruijn
 * Date: 7/26/21
 * Time: 11:24 AM
 */

namespace Appbakkers\Ethereum\Objects;

use Appbakkers\Ethereum\EchtFitTokenContract;
use Web3\Contract;
use Web3\Contracts\Ethabi;
use Web3\Contracts\Types\Address;

class EventLog
{

    /**
     * @var String
     */
    public $eventName;

    /**
     * @var array
     */
    public $event;

    /**
     * @var array
     */
    public $arguments;

    /**
     * @var String hash
     */
    public $transactionHash;

    /**
     * Contract address
     * @var String
     */
    public $address;

    /**
     * @var String
     */
    public $blockNumber;

    /**
     * @var Ethabi
     */
    private $abi;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * EventLog constructor.
     * @param $log
     */
    public function __construct($log)
    {
        $this->contract = (new EchtFitTokenContract())->contract;
        $this->abi = $this->contract->getEthabi();

        $this->address = $log->address;
        $this->blockNumber = $log->blockNumber;
        $this->transactionHash = $log->transactionHash;
        $this->decodeTopics($log);
    }

    /**
     * @param $log
     */
    private function decodeTopics($log){

        // Get topics
        $topics = $log->topics;

        // Decode funciton signature, this will set the event
        $this->decodeFunction($topics[0]);

        // If we have no event we can't decode
        if($this->event == null)
            return;

        // split indexed and non indexed parameters
        $indexed = [];
        $nonIndexed = [];
        foreach($this->event['inputs'] as $key => $input){
            if($input['indexed'] === true)
                $indexed[$key] = $input;
            else
                $nonIndexed[] = $input;
        }

        // Decode indexed
        foreach ($indexed as $key => $input){
            $value = $this->abi->decodeParameter(
                $input['type'], $topics[$key + 1]
            );
            $input['value'] = $value;
            $this->arguments[] = $input;
        }

        // And non indexed
        $nonIndexedTypes = [];
        foreach ($nonIndexed as $input){
            $nonIndexedTypes[] = $input['type'];
        }

        // Decode non indexed from single Data param
        $decodedNonIndexed = $this->abi->decodeParameters($nonIndexedTypes, $log->data);

        // Map each decoded param to it's own argument
        foreach ($nonIndexed as $key => $input){
            $input['value'] = $decodedNonIndexed[$key];
            $this->arguments[] = $input;
        }
    }

    /**
     * Decode the signature to a Event object using the contract ABI
     * @param $string
     * @return array|null
     */
    private function decodeFunction($string){
        $events = $this->contract->getEvents();
        foreach ($events as $name => $event){
            $signature = $this->abi->encodeEventSignature($this->contract->getEvents()[$name]);

            if($signature == $string) {
                $this->event = $event;
                $this->eventName = $name;
            }
        }

        return null;
    }
}