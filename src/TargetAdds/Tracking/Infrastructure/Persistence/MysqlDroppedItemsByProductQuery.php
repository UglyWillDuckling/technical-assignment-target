<?php

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductQuery;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Override;
use function Lambdish\Phunctional\map;

class MysqlDroppedItemsByProductQuery extends AggregateQuery implements DroppedItemsByProductQuery
{
    private const array AGGREGATE_FIELDS = ['total'];

    #[Override] protected function aggregateFields(): array
    {
        return self::AGGREGATE_FIELDS;
    }

    #[Override] public function matching(Criteria $criteria): DroppedItemsByProductCollection
    {
        return parent::matching($criteria);
    }

    #[Override] protected function select(): string
    {
        return 'd.sku, COUNT(d.sku) as total';
    }

    #[Override] protected function groupBy(): string
    {
        return 'd.sku';
    }

    protected function createResultCollection(Query $query)
    {
        $items = $query->getResult();
        $totalCount = (new Paginator($query))->count();

        return new DroppedItem\DroppedItemsByProductCollection(
            map(fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']), $items),
            $totalCount);
    }
}

