<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

readonly class ItemGetController
{
    public function __construct(private DroppedItemRepository $droppedItemRepository)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = (string)$request->GET('id');

        $item = $this->droppedItemRepository->search($id);

        return new JsonResponse(['item' => $item], 200, ['Access-Control-Allow-Origin' => '*']);
    }
}
