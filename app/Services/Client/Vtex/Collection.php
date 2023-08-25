<?php

namespace App\Services\Client\Vtex;

use App\Services\Client\Vtex\Config;

class Collection {
	private $config;

	public function __construct(Config $config) {
        $this->config = $config;
	}

	public function list(int $collectionId, int $pageSize) {
    	$uri = "{$this->config->getBaseUri()}catalog/pvt/collection/{$collectionId}/products?pageSize={$pageSize}";

		$client = $this->config->setClient();

		$response = $client->get($uri);

		$responseData = json_decode($response->getBody(), true);

		return $responseData;

	}


}