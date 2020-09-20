<?php

namespace App\DataFixtures;

use App\Entity\NearEarthObject;
use App\Service\Nasa\Adapter\NearEarthObjectAdapter;

class NearEarthObjectFixture extends BaseFixture
{
    /**
     * @throws \App\Service\Nasa\Exception\NotEnoughDataException
     */
    protected function loadData(): void
    {
        $data = json_decode(file_get_contents('tests/samples/near_earth_objects_response.json'), true);
        foreach ($data['near_earth_objects'] as $nearEarthObjectList) {
            foreach ($nearEarthObjectList as $nearEarthObjectData) {
                $nearEarthObjectAdapter = new NearEarthObjectAdapter($nearEarthObjectData);
                $nearEarthObject = new NearEarthObject();
                $nearEarthObject
                    ->setApproachDate($nearEarthObjectAdapter->getApproachDate())
                    ->setName($nearEarthObjectAdapter->getName())
                    ->setReference($nearEarthObjectAdapter->getReference())
                    ->setSpeed($nearEarthObjectAdapter->getSpeed())
                    ->setIsHazardous($nearEarthObjectAdapter->isHazardous());

                $this->manager->persist($nearEarthObject);
            }
        }
        $this->manager->flush();
    }
}