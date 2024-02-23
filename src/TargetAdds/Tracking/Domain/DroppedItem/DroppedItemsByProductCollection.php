<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

use Acme\Shared\Domain\Collection;

class DroppedItemsByProductCollection extends Collection {
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
        return DroppedItemsByProduct::class;
    }
}
