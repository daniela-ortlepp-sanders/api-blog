<?php

namespace App\Services\Client\Vtex;

use App\Services\Client\Vtex\Collection;
use App\Services\Client\Vtex\Sku;
use App\Services\Client\Vtex\Price;

class Endpoints {

	public function __construct() {
	}

	public function getSku(int $skuId) {
        $sku = app()->make(Sku::class);
        return $sku->get($skuId);
	}

	public function getPrice(int $skuId) {
        $price = app()->make(Price::class);
        return $price->get($skuId);
	}

	public function listCollections(int $collectionId, int $pageSize) {
        $collection = app()->make(Collection::class);
        return $collection->list($collectionId, $pageSize);
	}
}