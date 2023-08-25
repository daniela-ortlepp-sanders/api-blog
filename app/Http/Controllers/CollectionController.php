<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use GuzzleHttp\Client;

use App\Services\UtilService;
use App\Services\Client\Vtex\Endpoints;

class CollectionController extends Controller
{
    private $utilService;
    private $vtexEndpoint;

    public function __construct(UtilService $utilService, Endpoints $vtexEndpoint) {
        $this->utilService = $utilService;
        $this->vtexEndpoint = $vtexEndpoint;
    }

    public function getTopSkus(Request $request) {
        $collectionId = $request->query->get('id');
        $pageSize = $request->query->get('page') ?? 10;
        $collections = $this->vtexEndpoint->listCollections($collectionId, $pageSize);

        $skuList = [];
        try {
            foreach ($collections['Data'] as $col) {
                $id = $col['SkuId'];
                $product = $this->vtexEndpoint->getSku($id);
                $sku['id'] = $product['Id'];
                $sku['NameComplete'] = $product['NameComplete'];
                $sku['ProductDescription'] = $product['ProductDescription'];
                $sku['DetailUrl'] = $product['DetailUrl'];
                $sku['Images'] = $product['Images'];

                $prices = $this->getPrice($id);
                $skuPrice = $prices['prices']['items'][0]['listPrice'] ?? 0;
                $sku['price'] = $skuPrice;

                $promotionalPrice = $prices['prices']['items'][0]['sellingPrice'] ?? 0;
                $sku['promotionalPrice'] = $promotionalPrice;

                array_push($skuList, $sku);

            }

            return $this->utilService->jsonResponse('success', null, $skuList, 200);
        } catch (\Exception $e) {
            return $this->utilService->jsonResponse('error', $e->getMessage(), null, 500);
        }
    }

    public function getPrice($skuId) {
        try {
            $prices = $this->vtexEndpoint->getPrice($skuId);

            return [
                'prices' => $prices,
            ];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
