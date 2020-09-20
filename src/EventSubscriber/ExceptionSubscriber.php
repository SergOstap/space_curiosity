<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $response = $this->buildResponse($event->getThrowable());

        $event->setResponse(new JsonResponse($response['data'], $response['code']));
    }

    private function buildResponse(\Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return [
                'data' => [
                    'message' => 'Not found',
                ],
                'code' => Response::HTTP_NOT_FOUND,
            ];
        }

        return [
            'data' => [
                'message' => 'Internal Error',
            ],
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
        ];
    }
}