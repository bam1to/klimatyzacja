<?php

namespace App\Controller;

use Exception;

class BaseController
{
    protected string $strErrorDesc = '';
    protected string $strErrorHeader = '';
    protected string $responseData = '';

    public function __call($name, $arguments)
    {
        $this->sendOutput('', ['HTTP/1.1 404 Not Found']);
    }


    protected function getUriSergments(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        return $uri;
    }

    /**
     * Gets GET queries
     */
    protected function getQueryStringParams(): array
    {
        parse_str($_SERVER['QUERY_STRING'], $query);

        return $query;
    }

    /**
     * Gets POST response body
     */
    protected function getContent()
    {
        if ($_SERVER["REQUEST_METHOD"] !== 'POST') {
            throw new Exception('Method Not Allowed', 405);
        }

        return file_get_contents('php://input');
    }

    /**
     * Checks errors before sending response
     */
    protected function processOutput()
    {
        if (!$this->strErrorDesc) {
            $this->sendOutput(
                $this->responseData,
                ['Content-Type: application/json', 'HTTP/1.1 200 OK']
            );
        } else {
            $this->sendOutput(
                json_encode(['error' => $this->strErrorDesc]),
                ['Content-Type: application/json', $this->strErrorHeader]
            );
        }
    }

    /**
     * Send response
     */
    protected function sendOutput($data, $httpHeaders = [])
    {
        header_remove('Set-Cookie');

        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }

        echo $data;
        exit;
    }
}
