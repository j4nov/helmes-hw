<?php

namespace App\EventListener;

use App\Exception\NoSessionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NoSessionException) {

            $response = new JsonResponse(
                ['error' => 'No session available. Please log in again.'],
                Response::HTTP_BAD_REQUEST
            );
            $event->setResponse($response);
        }
    }
}
