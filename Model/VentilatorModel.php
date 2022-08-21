<?php

namespace App\Model;

use App\Dto\VentilatorDTO;
use Exception;
use PDO;

class VentilatorModel extends Database
{

    /**
     * Gets multiple ventilators records
     * @param integer $limit if limit equal 0, returns all records
     * @return array
     */
    public function getVentilators(): array
    {
        return $this->execute("SELECT * FROM ventilators ORDER BY id");
    }

    /**
     * Gets concrete ventilator
     * @return array
     */
    public function getVentilator(int $id): array
    {
        return $this->execute("SELECT * FROM ventilators WHERE id = :id", [['id', $id, PDO::PARAM_INT]]);
    }

    /**
     * Adds new ventilator record
     * @param VentilatorDTO $ventilatorData 
     */
    public function addVentilator(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['status', $ventilatorData->getStatus(), PDO::PARAM_BOOL],
            ['temperature', $ventilatorData->getTemperature(), PDO::PARAM_INT],
            ['power', $ventilatorData->getPower(), PDO::PARAM_INT],
            ['windingDirection', $ventilatorData->getWindingDirection(), PDO::PARAM_STR],
            ['modeSetting', $ventilatorData->getModeSettings(), PDO::PARAM_STR]
        ];

        $this->execute("
            INSERT INTO 
                ventilators (`status`, `temperature`, `fan_power`, `winding_direction`, `mode_settings`)
            VALUES (:status, :temperature, :power, :windingDirection, :modeSetting)", $insertedData);
    }

    /**
     * Switch ventilator status
     * @param VentilatorDTO $ventilatorData
     */
    public function switchVentilator(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['id', $ventilatorData->getId(), PDO::PARAM_INT],
            ['status', $ventilatorData->getStatus(), PDO::PARAM_BOOL]
        ];

        $this->execute("
            UPDATE ventilators SET status = :status
            WHERE id = :id
        ", $insertedData);
    }

    /**
     * Gets ventilator's temperature
     * @param int $id ventilator id
     */
    public function getTemperature(int $id): array
    {
        return $this->execute("SELECT id, temperature FROM ventilators WHERE id = :id", [['id', $id, PDO::PARAM_INT]]);
    }

    public function changeTemperature(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['id', $ventilatorData->getId(), PDO::PARAM_INT],
            ['temperature', $ventilatorData->getTemperature(), PDO::PARAM_INT]
        ];

        $this->execute("
            UPDATE ventilators SET temperature = :temperature
            WHERE id = :id
        ", $insertedData);
    }

    /**
     * Gets ventilator's power
     * @param int $id ventilator id
     */
    public function getPower(int $id): array
    {
        return $this->execute("SELECT id, fan_power FROM ventilators WHERE id = :id", [['id', $id, PDO::PARAM_INT]]);
    }

    public function changePower(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['id', $ventilatorData->getId(), PDO::PARAM_INT],
            ['power', $ventilatorData->getPower(), PDO::PARAM_INT]
        ];

        $this->execute("
            UPDATE ventilators SET fan_power = :power
            WHERE id = :id
        ", $insertedData);
    }

    public function changeWindingDirection(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['id', $ventilatorData->getId(), PDO::PARAM_INT],
            ['windingDirection', $ventilatorData->getWindingDirection(), PDO::PARAM_STR]
        ];

        $this->execute("
            UPDATE ventilators SET winding_direction = :windingDirection
            WHERE id = :id
        ", $insertedData);
    }

    public function changeModeSettings(VentilatorDTO $ventilatorData)
    {
        $insertedData = [
            ['id', $ventilatorData->getId(), PDO::PARAM_INT],
            ['modeSettings', $ventilatorData->getModeSettings(), PDO::PARAM_STR]
        ];

        $this->execute("
            UPDATE ventilators SET mode_settings = :modeSettings
            WHERE id = :id
        ", $insertedData);
    }
}
