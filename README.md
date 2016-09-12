# README #

Class for executing calls to EcomDash API

### What is this repository for? ###

* Just-do-the-job class for executing calls to EcomDash API
* Version 0.0.1

### How do I get set up? ###


```
#!php

require 'class.ecomdash_api.php';
```

### Usage ###
See examples.php for full examples.

Initialize:

```
#!php

$developer_key = 'rihfngkdhityr4re698574bfnvn'; // Subscription key from developer account
$account_integration_key = '675037560453y0854497y58'; // The API integration key for mobile from ecomdash account

$ecomdash_api = new Ecomdash_API($developer_key, $account_integration_key);
```
Make a simple GET call to API:

```
#!php

$request_method = 'GET'; // GET or POST
$function = 'product'; // API action without the first forward flash
$params_str = '?sku=953867384'; // Query parameters if $method is GET, Request body if $method is POST
$current_stock = $ecomdash_api->execute_action($request_method, $function, $params_str);
```

Make a POST call to API:

```
#!php

$request_method = 'POST';
$function = 'inventory/updateQuantityOnHand';
$payload = $inventory_to_sync_json;
$ecomdash_api->execute_action($request_method, $function, $payload);
```

### Who do I talk to? ###

* Repo owner or admin