<?php

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
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

    #[Override] protected function alias(): string {
        return 'd';
    }

    #[Override] protected function select(): string {
        return $this->alias().'.sku, COUNT('.$this->alias().'.sku) as total';
    }

    #[Override] protected function groupBy(): string {
        return $this->alias().'.sku';
    }

    protected function createResultCollection(Query $query): DroppedItemsByProductCollection
    {
        $items = $query->getResult();
        $totalCount = (new Paginator($query))->count();

        return new DroppedItemsByProductCollection(
            map(fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']), $items),
            $totalCount
        );
    }
}

