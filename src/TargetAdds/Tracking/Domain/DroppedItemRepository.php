<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;

interface DroppedItemRepository
{
  public function save(DroppedItem $droppedItem): void;

    public function search(string $id): DroppedItem;

    public function searchAll(): DroppedItemsCollection;

    public function matching(Criteria $criteria): DroppedItemsCollection;

    /** @return DroppedItem\DroppedItemsByProductCollection  */
    public function byProduct(Criteria $criteria): DroppedItem\DroppedItemsByProductCollection;

    /** @return DroppedItem\DroppedItemsByCustomerCollection  */
    public function byCustomer(Criteria $criteria): DroppedItem\DroppedItemsByCustomerCollection;
}
