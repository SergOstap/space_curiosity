<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\NearEarthObjectRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NearEarthObjectsController extends BaseController
{
    private NearEarthObjectRepository $nearEarthObjectRepository;
    private SerializerInterface $serializer;

    public function __construct(
        NearEarthObjectRepository $nearEarthObjectRepository,
        SerializerInterface $serializer
    ) {
        $this->nearEarthObjectRepository = $nearEarthObjectRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/neo/hazardous", name="app_near_earth_objects_hazardous")
     */
    public function hazardous(Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $asteroidsPaginator = $this->nearEarthObjectRepository->getHazardousPaginated($offset);

        return new Response(
            $this->serializer->serialize(
                [
                    'limit' => NearEarthObjectRepository::PER_PAGE_LIMIT,
                    'offset' => $offset,
                    'total' => $asteroidsPaginator->count(),
                    'data' => $asteroidsPaginator->getIterator()->getArrayCopy(),
                ],
                'json'
            ),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    /**
     * @Route("/neo/fastest", name="app_near_earth_objects_fastest")
     */
    public function fastest(Request $request): Response
    {
        $isHazardous = $request->query->getBoolean('hazardous', false);

        return new Response(
            $this->serializer->serialize($this->nearEarthObjectRepository->getOneFastest($isHazardous), 'json'),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}