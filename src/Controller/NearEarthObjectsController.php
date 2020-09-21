<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\NearEarthObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NearEarthObjectsController extends BaseController
{
    private NearEarthObjectRepository $nearEarthObjectRepository;

    public function __construct(NearEarthObjectRepository $nearEarthObjectRepository) {
        $this->nearEarthObjectRepository = $nearEarthObjectRepository;
    }

    /**
     * @Route("/neo/hazardous", name="app_near_earth_objects_hazardous")
     */
    public function hazardous(Request $request): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $asteroidsPaginator = $this->nearEarthObjectRepository->getHazardousPaginated($offset);

        return $this->serializedResponse(
            [
                'limit' => NearEarthObjectRepository::PER_PAGE_LIMIT,
                'offset' => $offset,
                'total' => $asteroidsPaginator->count(),
                'data' => $asteroidsPaginator->getIterator()->getArrayCopy(),
            ]
        );
    }

    /**
     * @Route("/neo/fastest", name="app_near_earth_objects_fastest")
     */
    public function fastest(Request $request): Response
    {
        $isHazardous = $request->query->getBoolean('hazardous', false);

        return $this->serializedResponse($this->nearEarthObjectRepository->getOneFastest($isHazardous));
    }
}