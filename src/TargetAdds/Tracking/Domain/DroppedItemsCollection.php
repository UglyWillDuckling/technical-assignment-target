<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Collection;

class DroppedItemsCollection extends Collection
{
    public function __construct(array $items, private readonly int $total_count)
    {
        parent::__construct($items);
    }

    public function countTotal(): int
    {
        return $this->total_count;
    }

    protected function type(): string
    {
        return DroppedItem::class;
    }
}
