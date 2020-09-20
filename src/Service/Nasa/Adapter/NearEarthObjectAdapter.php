<?php

declare(strict_types=1);

namespace App\Service\Nasa\Adapter;

use App\Service\Nasa\Exception\NotEnoughDataException;

class NearEarthObjectAdapter
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \DateTimeInterface
     * @throws NotEnoughDataException
     */
    public function getApproachDate(): \DateTimeInterface
    {
        $approachDate = $this->data['close_approach_data'][0]['close_approach_date'] ?? null;
        if ($approachDate === null) {
            throw new NotEnoughDataException();
        }

        return \DateTime::createFromFormat('Y-m-d', $approachDate);
    }

    /**
     * @return string
     * @throws NotEnoughDataException
     */
    public function getReference(): string
    {
        $reference = $this->data['neo_reference_id'] ?? null;
        if ($reference === null) {
            throw new NotEnoughDataException();
        }

        return $reference;
    }

    /**
     * @return string
     * @throws NotEnoughDataException
     */
    public function getName(): string
    {
        $name = $this->data['name'] ?? null;
        if ($name === null) {
            throw new NotEnoughDataException();
        }

        return $name;
    }

    /**
     * @return float
     * @throws NotEnoughDataException
     */
    public function getSpeed(): float
    {
        $speed = $this->data['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'] ?? null;
        if ($speed === null) {
            throw new NotEnoughDataException();
        }

        return (float)$speed;
    }

    /**
     * @return bool
     * @throws NotEnoughDataException
     */
    public function isHazardous(): bool
    {
        $isHazardous = $this->data['is_potentially_hazardous_asteroid'] ?? null;
        if ($isHazardous === null) {
            throw new NotEnoughDataException();
        }

        return $isHazardous;
    }
}