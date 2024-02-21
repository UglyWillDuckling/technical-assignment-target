<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomer;

interface DroppedItemRepository
{
  public function save(DroppedItem $droppedItem): void;

    public function searchAll(): DroppedItemsCollection;

    public function matching(Criteria $criteria): DroppedItemsCollection;

    /** @return array{int, DroppedItemsByProduct}  */
    public function byProduct(): array;

    /** @return array{int, DroppedItemsByCustomer}  */
    public function byCustomer(): array;
}
