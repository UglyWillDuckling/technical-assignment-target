<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

readonly class DroppedItemsByCustomer
{
    public function __construct(
        public string $sku,
        public string $customer_id,
        public int    $total
    ) {
    }
}
