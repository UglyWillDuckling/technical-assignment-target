<?php

namespace Acme\Apps\TargetAdds\Front\EventListener;

use Acme\TargetAdds\Tracking\Domain\DroppedItemNotFound;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
