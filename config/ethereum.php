<?php

return [
    /**
     * - The IP address or host name  of the Ethereum node the DAP is hosted on
     * - The "chain id of the ethereum network the nods runs on
     */
    'host' => env('ETHEREUM_HOST','http://94.213.158.62:8545'),
    'chain_id' => env('ETHEREUM_CHAIN_ID',1234),

    /**
     * The private key of the contract owner, this is used to sign all transaction.
     */
    'private_key' => env('CONTRACT_PRIVATE_KEY', "")

];