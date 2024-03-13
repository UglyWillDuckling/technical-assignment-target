<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

readonly class DroppedItemsByProduct
{
    public function __construct(
        public string $sku,
        public int    $total
    ) {
    }
}
