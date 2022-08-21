<?php

namespace App\config;

use Exception;

class Routing
{
    private array $routesMethods = [
        'ventilators' => ['GET'],
        'ventilator' => ['GET', 'POST'],
        'switch' => ['POST'],
        'temperature' => ['GET', 'POST'],
        'power' => ['GET', 'POST'],
        'windingDirection' => ['POST'],
        'modeSettings' => ['POST']
    ];

    /**
     * Shows accessable methods for this route
     */
    private function getRouteMethods(string $route): array
    {
        return $this->routesMethods[$route] ?? [];
    }

    public function checkRouteMethods(string $route): void
    {
        $routeMethods = $this->getRouteMethods($route);

        if (empty($routeMethods)) {
            echo "HTTP/1.1 404 Not Found";
            exit();
        }

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (!in_array(strtoupper($requestMethod), $routeMethods)) {
            echo "HTTP/1.1 405 Method Not Allowed";
            exit;
        }
    }
}
