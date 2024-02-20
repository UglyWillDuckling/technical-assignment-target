<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;

interface DroppedItemRepository
{
  public function save(DroppedItem $droppedItem): void;

    /** @return array{int, DroppedItem} */
    public function searchAll(): array;

    /** @return array{string, DroppedItem} */
    public function matching(Criteria $criteria): array;
}
