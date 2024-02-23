<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;

interface DroppedItemsByProductQuery
{
    public function matching(Criteria $criteria): DroppedItemsByProductCollection;
}

