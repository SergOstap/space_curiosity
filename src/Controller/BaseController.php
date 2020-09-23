<?php

declare(strict_types=1);

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController implements BaseControllerInterface
{
    private SerializerInterface $serializer;

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param mixed $response
     * @param int|null $status
     * @return Response
     */
    public function serializedResponse($response, ?int $status = 200): Response
    {
        return new Response(
            $this->serializer->serialize($response, 'json'),
            $status,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}