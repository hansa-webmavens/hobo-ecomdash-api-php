<?php
/**
 * Class for executing calls to EcomDash API
 */

class Ecomdash_API
{
	private $developer_key = ''; // Subscription key from developer account
	private $account_integration_key = ''; // The API integration key for mobile from ecomdash account
	private $url = 'https://ecomdash.azure-api.net/api/';

	/**
	 * Contructor
	 * @param string $developer_key           Subscription key from developer account
	 * @param string $account_integration_key The API integration key for mobile from ecomdash account
	 */
	public function __construct( $developer_key, $account_integration_key )
	{
		if ( !isset($developer_key) || $developer_key == '' || !isset($account_integration_key) || $account_integration_key == '' ) {
			throw new Exception('Invalid developer key or account integration key', 1);
		}
		$this->developer_key = $developer_key;
		$this->account_integration_key = $account_integration_key;
	}

	/**
	 * send GET request to ecomdash API
	 * @param  string $action API action without the first forward flash. E.g. "carriers/"
	 * @param  string $params Query parameters. E.g. "?sku=4613246"
	 * @return mixed
	 */
	private function send_GET_request( $action, $params = '' )
	{
		$headers = array();
		$headers[] = 'Ocp-Apim-Subscription-Key: ' . $this->developer_key;
		$headers[] = 'ecd-subscription-key: ' . $this->account_integration_key;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, $this->url . $action . $params);
		$result = json_decode(curl_exec($curl) , true);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ( curl_errno($curl)
				|| $httpcode != 200
				|| (!empty($result) && (isset($result['status']) && $result['status'] != 'Success'))
				) {
			$error_message = 'Error Processing Request: HTTP Code: ' . $httpcode . "\n" .
				var_export(curl_error($curl), true) . "\n" .
				var_export($result, 1);
			curl_close($curl);
			throw new Exception($error_message, 1);
		}
		curl_close($curl);

		return $result;
	}

	/**
	 * Send POST request to ecomdash API
	 * @param  string $action API action without the first forward flash. E.g. "Inventory/UpdateQuantityOnHand"
	 * @param  json   $params Request body
	 * @return mixed
	 */
	private function send_POST_request( $action, $params = '[]' )
	{
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Ocp-Apim-Subscription-Key: ' . $this->developer_key;
		$headers[] = 'ecd-subscription-key: ' . $this->account_integration_key;
		$headers[] = 'Content-Length: ' . strlen($params);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_URL, $this->url . $action);
		$result = json_decode(curl_exec($curl) , true);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ( curl_errno($curl)
				|| $httpcode != 200
				|| (!empty($result) && (isset($result['status']) && $result['status'] != 'Success'))
				) {
			$error_message = 'Error Processing Request: HTTP Code: ' . $httpcode . "\n" .
				var_export(curl_error($curl), true) . "\n" .
				var_export($result, 1);
			curl_close($curl);
			throw new Exception($error_message, 1);
		}
		curl_close($curl);

		return $result;
	}

	/**
	 * execute API call
	 * @param  string $method GET or POST
	 * @param  string $action $action API action without the first forward flash. E.g. "Inventory/UpdateQuantityOnHand"
	 * @param  mixed  $params Query parameters if $method is GET, Request body if $method is POST
	 * @return mixed
	 */
	public function execute_action( $method, $action, $params = '' )
	{
		switch ( $method ) {
			case 'GET':
				$response = $this->send_GET_request($action, $params);
				break;

			case 'POST':
				$response = $this->send_POST_request($action, $params);
				break;

			default:
				throw new Exception('Invalid request method (must be either POST or GET)', 1);
				break;
		}

		return $response;
	}

}
// eof