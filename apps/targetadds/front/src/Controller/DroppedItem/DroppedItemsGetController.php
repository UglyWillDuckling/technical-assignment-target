<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;

readonly class DroppedItemsGetController extends DroppedItemsController
{
    public function __construct(private DroppedItemRepository $droppedItemRepository){}

    #[\Override] protected function getItems(Criteria $criteria): iterable
    {
        return $this->droppedItemRepository->matching($criteria);
    }

    #[\Override] protected function itemsMapping(): callable
    {
        return fn(DroppedItem $droppedItem): array => [
            'sku' => $droppedItem->sku,
            'customer_id' => $droppedItem->customer_id,
            'created_at' => $droppedItem->created_at,
        ];
    }
}
