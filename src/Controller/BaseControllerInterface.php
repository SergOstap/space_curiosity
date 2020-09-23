<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

interface BaseControllerInterface
{
    public function serializedResponse($response, ?int $status = 200): Response;
}