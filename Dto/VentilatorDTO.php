<?php

namespace App\Dto;

use Exception;
use JsonSerializable;

class VentilatorDTO implements JsonSerializable
{
    private int $id;

    private ?bool $status;

    private ?int $temperature;

    private ?int $power;

    private ?string $windingDirection;

    private ?string $modeSetting;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): void
    {
        $this->status = $status;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(?int $temperature): void
    {
        $this->temperature = $temperature;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): void
    {
        $this->power = $power;
    }

    public function getWindingDirection(): ?string
    {
        return $this->windingDirection;
    }

    /**
     * @throws Exception Wrong winding direction
     * @see acceptable winding directions in ```$acceptableWindingDirections```
     */
    public function setWindingDirection(?string $windingDirection): void
    {
        $acceptableWindingDirections = ['front', 'right', 'left', 'down', 'top'];

        if (
            !in_array($windingDirection, $acceptableWindingDirections)
            && $windingDirection !== NULL
        ) {
            throw new Exception('Wrong winding direction', 400);
        }

        $this->windingDirection = $windingDirection;
    }

    public function getModeSettings(): ?string
    {
        return $this->modeSetting;
    }

    /**
     * @throws Exception Wrong mode settings
     * @see acceptable mode settings in ```$acceptableModeSettings```
     */
    public function setModeSettings(?string $modeSetting): void
    {
        $acceptableModeSettings = ['cool', 'auto', 'heat', 'dry'];

        if (!in_array($modeSetting, $acceptableModeSettings) && $modeSetting !== NULL) {
            throw new Exception('Wrong mode settings', 400);
        }

        $this->modeSetting = $modeSetting;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
