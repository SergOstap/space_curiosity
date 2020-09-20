<?php

declare(strict_types=1);

namespace App\Service\Nasa;

use App\Service\Nasa\Exception\NearEarthObjectsFetchException;
use App\Service\Nasa\Request\NearEarthObjectsRequest;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NasaGateway
{
    private string $nasaApiKey;
    private HttpClientInterface $httpClient;
    private const DOMAIN = 'https://api.nasa.gov';

    public function __construct(string $nasaApiKey, HttpClientInterface $httpClient)
    {
        $this->nasaApiKey = $nasaApiKey;
        $this->httpClient = $httpClient;
    }

    /**
     * @param NearEarthObjectsRequest $request
     * @return array
     * @throws NearEarthObjectsFetchException
     */
    public function fetchNearEarthObjects(NearEarthObjectsRequest $request): array
    {
        try {
            $params = array_merge($request->toArray(), ['api_key' => $this->nasaApiKey]);
            $response = $this->httpClient->request(
                'GET',
                self::DOMAIN . '/neo/rest/v1/feed?' . http_build_query($params)
            );

            return $response->toArray();
        } catch (ExceptionInterface $exception) {
            throw new NearEarthObjectsFetchException(
                'Can`t fetch Nasa Asteroids Neo',
                $exception->getCode(),
                $exception
            );
        }
    }
}