# mascot-api-client
PHP API Client

Example of usage:
```php
<?php
use mascotgaming\mascot\api\client\Client;

require __DIR__.'/vendor/autoload.php';

$client = new Client(array(
        'url' => 'https://api.mascot.games/v1/',
        'sslKeyPath' => __DIR__.'/ssl/apikey.pem',
));

var_export($client->listGames());
```
