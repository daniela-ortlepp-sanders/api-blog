<?php

namespace App\Services\Client\Vtex;

class Config {

    private $accountName;
	private $appKey;
	private $appToken;
    private $baseUri;
	private $environment;

	public function __construct() {
		$vtexVariables = json_decode($this->loadVtexVariables(), true);
		$this->accountName = $vtexVariables['accountName'];
		$this->appKey      = $vtexVariables['appKey'];
		$this->appToken    = $vtexVariables['appToken'];
		$this->environment = $vtexVariables['environment'];

        $this->baseUri     = "https://{$this->accountName}.{$this->environment}.com.br/api/";
	}

	public function getHeaders() {
		$headers = array();
		$headers['Accept'] = 'application/json';
		$headers['Content-Type'] = 'application/json';
		$headers['X-VTEX-API-AppKey'] = $this->appKey;
		$headers['X-VTEX-API-AppToken'] = $this->appToken;

		return $headers;
	}

	public function setClient() {
		return new \GuzzleHttp\Client([
			'headers' => $this->getHeaders()
		]);
	}

    public function loadVtexVariables() {
        $accountName = \Config::get('variables.vtex_account_name');
        $appKey = \Config::get('variables.vtex_app_key');
        $appToken = \Config::get('variables.vtex_app_token');
        $environment = \Config::get('variables.vtex_environment');

        return json_encode([
            'accountName' => $accountName,
            'appKey' => $appKey,
            'appToken' => $appToken,
            'environment' => $environment,
        ]);
    }

    public function getBaseUri() {
        return $this->baseUri;
    }

    public function getAccountName() {
        return $this->accountName;
    }

    public function getEnvironment() {
        return $this->environment;
    }

}