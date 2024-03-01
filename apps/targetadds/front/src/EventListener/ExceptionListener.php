<?php

namespace Acme\Apps\TargetAdds\Front\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\TargetAdds\Tracking\Domain\DroppedItemNotFound;

use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof DroppedItemNotFound) {
            $code = Response::HTTP_NOT_FOUND;
        } else {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $responseData = [
            'error' => [
                'code' => $code,
                'message' => $exception->getMessage()
            ]
        ];

        $event->setResponse(new JsonResponse($responseData, $code));
    }
}

