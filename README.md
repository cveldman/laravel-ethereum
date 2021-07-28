

## Installeren
```shell
php artisan vendor:publish --tag=ethereum-migrations
```

## Configureren
```dotenv
ETHEREUM_HOST=94.213.158.62
CONTRACT_ADDRESS=
CONTRACT_WALLET_ADDRESS=
```

## Examples

### Versturen van tokens
```php 
try 
{
    $address = '0x00000000000000000000000000000000000';
    $tokens = 1;

    EthereumContract::mint($address, $tokens);
    // OR
    auth()->user()->mint($tokens);
} 
catch(Exception $exception) 
{

}
```

### Betalen met tokens
```php 
try 
{
    $address = '';
    $tokens = 1;

    EthereumContract::mint($address, $tokens);
    // OR
    auth()->user()->mint($tokens);
} 
catch(Exception $exception) 
{

}
```

### Verifieren van betaling
```php 
try 
{

} 
catch(Exception $exception) 
{

}
```

TODO: Uitleggen over verifieren van betaling

Als een transactie gelukt is dan return hij een id van de transactie.
Er moet een worker job komen die elke minuut checkt of pending transacties gelukt
zijn en hierbij de gekoppelde actie (product beschikbaar stellen) verwerken.


Error handling

```php

try {
    // Use Stripe's library to make requests...
} catch(\Stripe\Exception\CardException $e) {
    // Since it's a decline, \Stripe\Exception\CardException will be caught
    echo 'Status is:' . $e->getHttpStatus() . '\n';
    echo 'Type is:' . $e->getError()->type . '\n';
    echo 'Code is:' . $e->getError()->code . '\n';
    // param is '' in this case
    echo 'Param is:' . $e->getError()->param . '\n';
    echo 'Message is:' . $e->getError()->message . '\n';
} catch (\Stripe\Exception\RateLimitException $e) {
    // Too many requests made to the API too quickly
} catch (\Stripe\Exception\InvalidRequestException $e) {
    // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Exception\AuthenticationException $e) {
    // Authentication with Stripe's API failed
    // (maybe you changed API keys recently)
} catch (\Stripe\Exception\ApiConnectionException $e) {
    // Network communication with Stripe failed
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Display a very generic error to the user, and maybe send
    // yourself an email
} catch (Exception $e) {
    // Something else happened, completely unrelated to Stripe
}
```