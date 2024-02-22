<?php
/**
 * DroppedItemsGetController
 *
 * @copyright Copyright © 2024 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;

readonly class DroppedItemsByCustomerController extends DroppedItemsController
{
    public function __construct(private DroppedItemRepository $droppedItemRepository){}

    #[\Override] protected function getItems(Criteria $criteria): iterable
    {
        return $this->droppedItemRepository->byCustomer($criteria);
    }

    #[\Override] protected function itemsMapping(): callable
    {
        return fn(DroppedItem\DroppedItemsByCustomer $droppedItem): array => [
            'sku' => $droppedItem->sku,
            'customer_id' => $droppedItem->customer_id,
            'total' => $droppedItem->total,
        ];
    }
}
