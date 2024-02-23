<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;

interface DroppedItemRepository
{
  public function save(DroppedItem $droppedItem): void;

  public function saveCollection(DroppedItemsCollection $coll): void;

    public function search(string $id): DroppedItem;

    public function searchAll(): DroppedItemsCollection;

    public function matching(Criteria $criteria): DroppedItemsCollection;
}
