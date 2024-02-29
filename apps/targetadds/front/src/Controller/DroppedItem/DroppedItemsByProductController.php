<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductQuery;

readonly class DroppedItemsByProductController extends DroppedItemsController
{
    public function __construct(private DroppedItemsByProductQuery $byProductQuery){}

    protected function getItems(Criteria $criteria): DroppedItemsByProductCollection
    {
        return $this->byProductQuery->matching($criteria);
    }

    protected function itemsMapping(): callable
    {
        return fn(DroppedItemsByProduct $droppedItem): array => [
            'sku' => $droppedItem->sku,
            'total' => $droppedItem->total,
        ];
    }
}
