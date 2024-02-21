<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Collection;

class DroppedItemsCollection extends Collection {
    protected function type(): string
    {
        return DroppedItem::class;
    }
}
