<?php

namespace App\Services\Client\Vtex;

use Illuminate\Http\Request;

use App\Services\Client\Vtex\Config;

class Price {
	private $config;

	public function __construct(Config $config) {
        $this->config = $config;
	}

	public function get(int $skuId) {
		$uri = "{$this->config->getBaseUri()}checkout/pub/orderForms/simulation?RnbBehavior=0";

		$client = $this->config->setClient();

		$body = '{
			"items": [
			  {
				"id": "' . $skuId . '",
				"quantity": 1,
				"seller": "1"
			  }
			]
		  }';

		#$request = new Request('POST', $uri, $headers, $body);

		$response = $client->post($uri, ['body' => $body]);
		#$response = $client->sendAsync($request)->wait();

		$responseData = json_decode($response->getBody(), true);

		return $responseData;
	}


}