<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

class DroppedItemsByCustomer {
    public function __construct(
        public readonly string $sku,
        public readonly string $customer_id,
        public readonly int $total
    ) {}
}
