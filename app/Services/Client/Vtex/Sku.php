<?php

namespace App\Services\Client\Vtex;

use App\Services\Client\Vtex\Config;

class Sku {
	private $config;

	public function __construct(Config $config) {
        $this->config = $config;
	}

	public function get(int $skuId) {
    	$uri = "{$this->config->getBaseUri()}catalog_system/pvt/sku/stockkeepingunitbyid/{$skuId}";

		$client = $this->config->setClient();

		$response = $client->get($uri);

		$responseData = json_decode($response->getBody(), true);

		return $responseData;

	}


}