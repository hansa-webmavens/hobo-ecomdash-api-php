<?php
// --- general settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . 'class.ecomdash_api.php';

// initialize
$developer_key = 'rihfngkdhityr4re698574bfnvn'; // Subscription key from developer account
$account_integration_key = '675037560453y0854497y58'; // The API integration key for mobile from ecomdash account

$ecomdash_api = new Ecomdash_API($developer_key, $account_integration_key);

// make a GET request to API
try {
	$request_method = 'GET'; // GET or POST
	$function = 'product'; // API action without the first forward flash
	$params_str = '?sku=953867384'; // Query parameters if $method is GET, Request body if $method is POST
	$ecomdash_products_current_stock = $ecomdash_api->execute_action($request_method, $function, $params_str);
	echo PHP_EOL . 'Get current stock for product success.' . PHP_EOL;
} catch (Exception $e) {
	echo PHP_EOL . 'Get current stock for product failed:' . PHP_EOL . $e->getMessage();
}

// make a POST request to API
$inventory_to_sync_json = '
[
	{
		"Sku" : "54099",
		"Quantity" : 8
	},
	{
		"Sku" : "54102",
		"Quantity" : 20
	}
]';
try {
	$request_method = 'POST';
	$function = 'inventory/updateQuantityOnHand';
	$payload = $inventory_to_sync_json;
	$ecomdash_api->execute_action($request_method, $function, $payload);
	echo PHP_EOL . 'Update inventory on hand to Ecomdash success.' . PHP_EOL;
} catch (Exception $e) {
	echo PHP_EOL . 'Update inventory on hand to Ecomdash failed:' . PHP_EOL . $e->getMessage();
}

exit();
// eof