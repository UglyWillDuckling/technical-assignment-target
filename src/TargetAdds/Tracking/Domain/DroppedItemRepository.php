<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;

interface DroppedItemRepository
{
  public function save(DroppedItem $droppedItem): void;

    /** @return list{int, DroppedItem} */
    public function searchAll(): array;

    /** @return list{string, int} */
    public function matching(Criteria $criteria): array;
}
