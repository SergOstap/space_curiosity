<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NearEarthObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=NearEarthObjectRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(name="neo_speed_idx", columns={"speed"}),
 *     @ORM\Index(name="neo_approach_date_idx", columns={"approach_date"})
 * })
 * @UniqueEntity("reference")
 */
class NearEarthObject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $approachDate;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $reference;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=10)
     */
    private float $speed;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isHazardous;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApproachDate(): \DateTimeInterface
    {
        return $this->approachDate;
    }

    public function setApproachDate(\DateTimeInterface $approachDate): self
    {
        $this->approachDate = $approachDate;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function isHazardous(): bool
    {
        return $this->isHazardous;
    }

    public function setIsHazardous(bool $isHazardous): self
    {
        $this->isHazardous = $isHazardous;

        return $this;
    }
}
