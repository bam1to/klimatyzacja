<?php

use App\config\Routing;
use App\Controller\VentilatorController;
$loader = require_once './vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if ((isset($uri[1]) && $uri[1] != 'api') || !isset($uri[2])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

(new Routing())->checkRouteMethods($uri[2]);

$objFeedController = new VentilatorController();
$strMethodName = 'action' . ucfirst($uri[2]);
$objFeedController->{$strMethodName}();
