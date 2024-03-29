<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Aggregate\AggregateRoot;
use DateTime;

final class DroppedItem extends AggregateRoot
{
    public readonly DateTime $created_at;

    public function __construct(
        private readonly string $id,
        public readonly string $customer_id,
        public readonly string $customer_email,
        public readonly string $sku
    ) {
        $this->created_at = new DateTime('now');
    }
}
