<?php

declare(strict_types=1);

namespace App\Service\Nasa;

use App\Entity\NearEarthObject;
use App\Repository\NearEarthObjectRepository;
use App\Service\Nasa\Adapter\NearEarthObjectAdapter;
use App\Service\Nasa\Exception\NotEnoughDataException;
use App\Service\Nasa\Request\NearEarthObjectsRequest;
use Doctrine\ORM\EntityManagerInterface;

class NasaNearEarthObjectsSynchronizer
{
    private NasaGateway $nasaGateway;
    private EntityManagerInterface $entityManager;
    private NearEarthObjectRepository $nearEarthObjectRepository;

    public function __construct(
        NasaGateway $nasaGateway,
        EntityManagerInterface $entityManager,
        NearEarthObjectRepository $nearEarthObjectRepository
    ) {
        $this->nasaGateway = $nasaGateway;
        $this->entityManager = $entityManager;
        $this->nearEarthObjectRepository = $nearEarthObjectRepository;
    }

    public function synchronize(NearEarthObjectsRequest $request)
    {
        $data = $this->nasaGateway->fetchNearEarthObjects($request);
        foreach ($data['near_earth_objects'] as $nearEarthObjectList) {
            foreach ($nearEarthObjectList as $nearEarthObjectData) {
                $this->handleData($nearEarthObjectData);
            }
        }
        $this->entityManager->flush();
    }

    private function handleData(array $data)
    {
        try {
            $nearEarthObjectAdapter = new NearEarthObjectAdapter($data);
            $existingObject = $this->nearEarthObjectRepository->findOneBy(
                ['reference' => $nearEarthObjectAdapter->getReference()]
            );
            if ($existingObject) {
                return;
            }
            $nearEarthObject = new NearEarthObject();
            $nearEarthObject
                ->setApproachDate($nearEarthObjectAdapter->getApproachDate())
                ->setName($nearEarthObjectAdapter->getName())
                ->setReference($nearEarthObjectAdapter->getReference())
                ->setSpeed($nearEarthObjectAdapter->getSpeed())
                ->setIsHazardous($nearEarthObjectAdapter->isHazardous());
            $this->entityManager->persist($nearEarthObject);
        } catch (NotEnoughDataException $exception) {
            // TODO maybe we need to log this
        }
    }
}