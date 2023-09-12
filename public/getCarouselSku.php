<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit();
}

require __DIR__.'/../vendor/autoload.php';
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$allowedMethods = array('GET');

$requestMethod = strtoupper($_SERVER['REQUEST_METHOD']);

if (!in_array($requestMethod, $allowedMethods)) {
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method Not Allowed", true, 405);
    exit;
}

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
$request = Request::create("/api/top-skus?id=231&page=10", 'GET');
$request->headers->set('Accept', 'application/json');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Authorization', "Bearer {$token}");

$response = $app->handle($request);
$responseBody = json_decode($response->getContent(), true);

header("Content-Type: application/json");
echo json_encode($responseBody);
exit();

?>