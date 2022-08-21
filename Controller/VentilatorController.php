<?php

namespace App\Controller;

use App\Dto\VentilatorDTO;
use App\Model\VentilatorModel;
use Error;
use Exception;

class VentilatorController extends BaseController
{

    /**
     * 1. Route: /api/ventilators 
     * 2. Methods: GET 
     * 3. Gets list of ventilators
     * 4. Params: Empty
     */
    public function actionVentilators()
    {
        try {
            $VentilatorModel = new VentilatorModel();
            $ventilators = $VentilatorModel->getVentilators();

            $response = [];

            foreach ($ventilators as $ventilator) {
                $ventilatorDTO = new VentilatorDTO();

                $ventilatorDTO->setId($ventilator['id']);
                $ventilatorDTO->setStatus($ventilator['status']);
                $ventilatorDTO->setTemperature($ventilator['temperature']);
                $ventilatorDTO->setPower($ventilator['fan_power']);
                $ventilatorDTO->setWindingDirection($ventilator['winding_direction']);
                $ventilatorDTO->setModeSettings($ventilator['mode_settings']);

                $response[] = $ventilatorDTO;
            }

            $this->responseData = json_encode($response);
        } catch (Error $e) {
            $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
            $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/ventilator
     * 2. Methods: GET, POST 
     * 3. Gets concrete ventilator or adds new ventilator
     * 4. Params: Get => id; Post => status, temperature, fan_power, winding_direction, mode_settings
     */
    public function actionVentilator()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // getting concrete ventilator by id
        if ($requestMethod === 'GET') {
            try {
                $queryParams = $this->getQueryStringParams();

                if (!isset($queryParams['id'])) {
                    throw new Exception('Nie właściwe GET żądanie.', 400);
                }

                $id = $queryParams['id'];

                $ventilatorModel = new VentilatorModel();
                $response = $ventilatorModel->getVentilator($id);
                $result = json_encode([]);

                if (!empty($response)) {
                    $response = $response[0];

                    // convert to DTO
                    $ventilatorDTO = new VentilatorDTO();

                    $ventilatorDTO->setId($response['id']);
                    $ventilatorDTO->setStatus($response['status']);
                    $ventilatorDTO->setTemperature($response['temperature']);
                    $ventilatorDTO->setPower($response['fan_power']);
                    $ventilatorDTO->setWindingDirection($response['winding_direction']);
                    $ventilatorDTO->setModeSettings($response['mode_settings']);

                    $result = json_encode($ventilatorDTO);
                }

                $this->responseData = $result;
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // adding new record to ventilators table
        if ($requestMethod === 'POST') {
            try {
                $requestData = $this->getContent();
                $requestData = json_decode($requestData, true);

                if (!isset($requestData['ventilator'])) {
                    throw new Exception('Nie właściwe POST żądanie.', 400);
                }

                $ventilatorData = $requestData['ventilator'];

                // convert to dto
                $ventilatorDTO = new VentilatorDTO();
                $ventilatorDTO->setStatus($ventilatorData['status'] ?? throw new Exception("Pole 'status' nie może być puste", 422));
                $ventilatorDTO->setTemperature($ventilatorData['temperature'] ?? throw new Exception("Pole 'temperature' nie może być puste", 422));
                $ventilatorDTO->setPower($ventilatorData['power'] ?? throw new Exception("Pole 'power' nie może być puste", 422));
                $ventilatorDTO->setWindingDirection($ventilatorData['windingDirection'] ?? throw new Exception("Pole 'windingDirection' nie może być puste", 422));
                $ventilatorDTO->setModeSettings($ventilatorData['modeSetting'] ?? throw new Exception("Pole 'modeSetting' nie może być puste", 422));

                $ventilatorModel = new VentilatorModel();
                $ventilatorModel->addVentilator($ventilatorDTO);

                $this->responseData = json_encode([]);
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/switch
     * 2. Methods: POST 
     * 3. Switch ventilator status (turn on => true, turn off => false)
     * 4. Params: Post => id, (bool)status
     */
    public function actionSwitch()
    {
        try {
            $requestData = $this->getContent();
            $requestData = json_decode($requestData, true);

            if (!isset($requestData['ventilator'])) {
                throw new Exception('Nie właściwe POST żądanie.', 400);
            }

            $ventilatorData = $requestData['ventilator'];

            // convert to dto
            $ventilatorDTO = new VentilatorDTO();
            $ventilatorDTO->setId($ventilatorData['id'] ?? throw new Exception("Pole 'id' nie może być puste", 422));
            $ventilatorDTO->setStatus($ventilatorData['status'] ?? throw new Exception("Pole 'status' nie może być puste", 422));

            $ventilatorModel = new VentilatorModel();
            $ventilatorModel->switchVentilator($ventilatorDTO);

            $this->responseData = json_encode([]);
        } catch (Exception $exception) {
            $this->strErrorDesc = $exception->getMessage();
            $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
        } catch (Error $error) {
            $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
            $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/temperature
     * 2. Methods: GET, POST 
     * 3. Checks temperature or set it
     * 4. Params: Get => id; Post => id, temperature
     */
    public function actionTemperature()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // getting ventilator temperature
        if ($requestMethod === 'GET') {
            try {
                $queryParams = $this->getQueryStringParams();

                if (!isset($queryParams['id'])) {
                    throw new Exception('Nie właściwe GET żądanie.', 400);
                }

                $id = $queryParams['id'];

                $ventilatorModel = new VentilatorModel();
                $response = $ventilatorModel->getTemperature($id);
                $result = json_encode([]);

                if (!empty($response)) {
                    $response = $response[0];

                    // convert to DTO
                    $ventilatorDTO = new VentilatorDTO();

                    $ventilatorDTO->setId($response['id']);
                    $ventilatorDTO->setTemperature($response['temperature']);

                    $result = json_encode($ventilatorDTO);
                }

                $this->responseData = $result;
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // change temperature
        if ($requestMethod === 'POST') {
            try {
                $requestData = $this->getContent();
                $requestData = json_decode($requestData, true);

                if (!isset($requestData['ventilator'])) {
                    throw new Exception('Nie właściwe POST żądanie.', 400);
                }

                $ventilatorData = $requestData['ventilator'];

                // convert to dto
                $ventilatorDTO = new VentilatorDTO();

                $ventilatorDTO->setId($ventilatorData['id']);
                $ventilatorDTO->setTemperature($ventilatorData['temperature'] ?? throw new Exception("Pole 'temperature' nie może być puste", 422));

                $ventilatorModel = new VentilatorModel();
                $ventilatorModel->changeTemperature($ventilatorDTO);

                $this->responseData = json_encode([]);
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/power
     * 2. Methods: GET, POST 
     * 3. Checks power or set it
     * 4. Params: Get => id; Post => id, power
     */
    public function actionPower()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        // getting ventilator temperature
        if ($requestMethod === 'GET') {
            try {
                $queryParams = $this->getQueryStringParams();

                if (!isset($queryParams['id'])) {
                    throw new Exception('Nie właściwe GET żądanie.', 400);
                }

                $id = $queryParams['id'];

                $ventilatorModel = new VentilatorModel();
                $response = $ventilatorModel->getPower($id);
                $result = json_encode([]);

                if (!empty($response)) {
                    $response = $response[0];

                    // convert to DTO
                    $ventilatorDTO = new VentilatorDTO();

                    $ventilatorDTO->setId($response['id']);
                    $ventilatorDTO->setPower($response['fan_power']);

                    $result = json_encode($ventilatorDTO);
                }

                $this->responseData = $result;
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // change temperature
        if ($requestMethod === 'POST') {
            try {
                $requestData = $this->getContent();
                $requestData = json_decode($requestData, true);

                if (!isset($requestData['ventilator'])) {
                    throw new Exception('Nie właściwe POST żądanie.', 400);
                }

                $ventilatorData = $requestData['ventilator'];

                // convert to dto
                $ventilatorDTO = new VentilatorDTO();

                $ventilatorDTO->setId($ventilatorData['id']);
                $ventilatorDTO->setPower($ventilatorData['power'] ?? throw new Exception("Pole 'power' nie może być puste", 422));

                $ventilatorModel = new VentilatorModel();
                $ventilatorModel->changePower($ventilatorDTO);

                $this->responseData = json_encode([]);
            } catch (Exception $exception) {
                $this->strErrorDesc = $exception->getMessage();
                $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
            } catch (Error $error) {
                $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
                $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/windingDirection
     * 2. Methods: POST 
     * 3. Set ventilator winding direction (Front, right, left, down, top)
     * 4. Params: Post => id, winding direction
     */
    public function actionWindingDirection()
    {
        try {
            $requestData = $this->getContent();
            $requestData = json_decode($requestData, true);

            if (!isset($requestData['ventilator'])) {
                throw new Exception('Nie właściwe POST żądanie.', 400);
            }

            $ventilatorData = $requestData['ventilator'];

            // convert to dto
            $ventilatorDTO = new VentilatorDTO();
            $ventilatorDTO->setId($ventilatorData['id'] ?? throw new Exception("Pole 'id' nie może być puste", 422));
            $ventilatorDTO->setWindingDirection($ventilatorData['windingDirection'] ?? throw new Exception("Pole 'windingDirection' nie może być puste", 422));

            $ventilatorModel = new VentilatorModel();
            $ventilatorModel->changeWindingDirection($ventilatorDTO);

            $this->responseData = json_encode([]);
        } catch (Exception $exception) {
            $this->strErrorDesc = $exception->getMessage();
            $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
        } catch (Error $error) {
            $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
            $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        // send output
        $this->processOutput();
    }

    /**
     * 1. Route: /api/modeSettings
     * 2. Methods: POST 
     * 3. Set ventilator mode settings (cool, auto, heat, dry)
     * 4. Params: Post => id, mode settings
     */
    public function actionModeSettings()
    {
        try {
            $requestData = $this->getContent();
            $requestData = json_decode($requestData, true);

            if (!isset($requestData['ventilator'])) {
                throw new Exception('Nie właściwe POST żądanie.', 400);
            }

            $ventilatorData = $requestData['ventilator'];

            // convert to dto
            $ventilatorDTO = new VentilatorDTO();
            $ventilatorDTO->setId($ventilatorData['id'] ?? throw new Exception("Pole 'id' nie może być puste", 422));
            $ventilatorDTO->setModeSettings($ventilatorData['modeSettings'] ?? throw new Exception("Pole 'modeSettings' nie może być puste", 422));

            $ventilatorModel = new VentilatorModel();
            $ventilatorModel->changeModeSettings($ventilatorDTO);

            $this->responseData = json_encode([]);
        } catch (Exception $exception) {
            $this->strErrorDesc = $exception->getMessage();
            $this->strErrorHeader = 'HTTP/1.1 ' . $exception->getCode();
        } catch (Error $error) {
            $this->strErrorDesc = 'Coś poszło nie tak, skontaktuj się z administratorem.';
            $this->strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
        }

        // send output
        $this->processOutput();
    }
}
