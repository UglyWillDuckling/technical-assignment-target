<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

class DroppedItemsByProduct {
    public function __construct(
        public readonly string $sku,
        public readonly int $total
    ) {}
}
