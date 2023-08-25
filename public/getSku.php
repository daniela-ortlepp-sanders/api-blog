<?php
require __DIR__.'/../vendor/autoload.php';
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$app = require_once __DIR__.'/../bootstrap/app.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

// Login
$request = Request::create('/api/login', 'POST');
$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('X-API-KEY', env('API_KEY'));

$response = $app->handle($request);
$responseBody = json_decode($response->getContent(), true);
$token = $responseBody["authorization"]["token"] ?? null;

// Get Skus
$request = Request::create("/api/sku?produto={$_GET['sku']}", 'GET');
$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Authorization', "Bearer {$token}");

$response = $app->handle($request);
$responseBody = json_decode($response->getContent(), true);

header("Content-Type: application/json");
echo json_encode($responseBody);
exit();

?>