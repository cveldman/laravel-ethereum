

## Installeren
```shell
php artisan vendor:publish --tag=ethereum-migrations
```

## Configureren
Voeg onderstaande waarden toe in .env file

```dotenv
ETHEREUM_HOST=94.213.158.62
ETHEREUM_CHAIN_ID=1234
CONTRACT_ADDRESS="0x3776eF174B761e4160EDa88faB4Ce2806d305379"
CONTRACT_OWNER_ADDRESS="0xe5423bab00679e0768d6ded41f98e23cccbee5cf"
CONTRACT_PRIVATE_KEY=""

```

## Examples

### Token balance checken van een gebruiker

```php
try
{
	// Hex address of the users wallet
    $address = '0x00000000000000000000000000000000000'; 
	(new EchtFitTokenContract())->balance($address, $tokens);

	 // OR when using trait
    auth()->user()->balance();

}catch(Exception $ex)
{
	
}
```

### Schenken van tokens aan een gebruiker
```php 
try 
{

    $tokens = 1;
    // Hex address of the users wallet
    $address = '0x00000000000000000000000000000000000'; 
    $transactionHash = 	(new EchtFitTokenContract())->mint($address, $tokens);
	
    // OR when using trait
    $transactionHash = auth()->user()->mint($tokens);
} 
catch(Exception $exception) 
{

}
```

### Token van een gebruiker innemen/verzilveren

```php 
try 
{
	
    $tokens = 1;

	// Hex address of the users wallet
    $userAddress = '0x00000000000000000000000000000000000'; 
    $contractAddress = config('contract.owner');
    
    $transactionHash = (new EchtFitTokenContract())->transferFrom($userAddress, $contractAddres, $tokens);

    // OR
    $transactionHash = auth()->user()->transfer($tokens);
} 
catch(Exception $exception) 
{

}
```

### Verifieren van transacties

>Als een transactie gelukt is dan return hij een id van de transactie. Er moet een worker job komen die b.v. elke minuut checkt of pending transacties gelukt zijn en hierbij de gekoppelde actie (product beschikbaar stellen) verwerken.


#### Aan de hand van transaction hash

```php 
try 
{
	$transcationHash = "0x00000000000000000000000000000000000"

	$logService = new EchtFitTtransactionService();
	
	$receipt = $logService->getTransactionReceipt($transactionHash);

	if($receipt != null){
		if($receipt->status == "0x1")
		{
			// sucess, transatie is afgehandeld en voltooid
			// $receipt->decodedLog verteld meer over de transactie, 
			// wat er is gebeurt & hoeveel tokens er verstuurd zijn.
			// $receipt->decodedLogs[0]->arguments
			
		} else
		{
			// Failure
		}
	}else
		// Status pending, or transaction doesn't exist
	
	
} 
catch(Exception $exception) 
{

}
```

#### Via Event logs

```php
try
{
	$service = new EchtFitTokenLogService();
	
	// Hex address of the users wallet
    $walletAddress = '0x00000000000000000000000000000000000';
    
	$eventLogs = $service->getAllLogsForWallet($walletAddress);
	
	foreach($eventLogs as $decodedLog) {
		// decodedLog verteld meer over de transactie en bevat o.a. de transaction hash, 
		// wat er is gebeurt & hoeveel tokens er verstuurd zijn.
		// decodedLog->arguments
	}
}
catch(Exception $ex)
{

}
```

Error handling

```php

try {
    // Use the library to make requests...
} catch (InsufficientAllowanceException $e)
	// The allowance is incorrect, this might mean the user doesn't have a active subscription.
} catch (TransactionFailedException $e) {
	// The actual transaction could not be created check the $error property and the $message for more info.
} catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
}
```