<?php

declare(strict_types=1);

namespace App\Service\Nasa\Request;

use App\Service\Nasa\Exception\InvalidDateIntervalException;

class NearEarthObjectsRequest
{
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;

    /**
     * AsteroidsNeoWsRequest constructor.
     * @param \DateTimeInterface $startDate
     * @param \DateTimeInterface $endDate
     * @throws InvalidDateIntervalException
     */
    public function __construct(\DateTimeInterface $startDate, \DateTimeInterface $endDate)
    {
        $now = new \DateTime();
        if ($startDate > $now || $endDate > $now || $startDate > $endDate) {
            throw new InvalidDateIntervalException();
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function toArray(): array
    {
        return [
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),
        ];
    }
}