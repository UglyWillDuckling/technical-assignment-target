<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomerCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomerQuery;

readonly class DroppedItemsByCustomerController extends DroppedItemsController
{
    public function __construct(private DroppedItemsByCustomerQuery $byCustomerQuery)
    {
    }

    #[\Override] protected function getItems(Criteria $criteria): DroppedItemsByCustomerCollection
    {
        return $this->byCustomerQuery->matching($criteria);
    }

    #[\Override] protected function itemsMapping(): callable
    {
        return fn (DroppedItem\DroppedItemsByCustomer $droppedItem): array => [
            'sku' => $droppedItem->sku,
            'customer_id' => $droppedItem->customer_id,
            'total' => $droppedItem->total,
        ];
    }
}
