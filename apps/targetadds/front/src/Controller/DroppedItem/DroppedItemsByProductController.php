<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;

readonly class DroppedItemsByProductController extends DroppedItemsController
{
    public function __construct(private DroppedItemRepository $droppedItemRepository){}

    protected function getItems(Criteria $criteria): iterable
    {
        return $this->droppedItemRepository->byProduct($criteria);
    }

    protected function itemsMapping(): callable
    {
        return fn(DroppedItem\DroppedItemsByProduct $droppedItem): array => [
            'sku' => $droppedItem->sku,
            'total' => $droppedItem->total,
        ];
    }
}
