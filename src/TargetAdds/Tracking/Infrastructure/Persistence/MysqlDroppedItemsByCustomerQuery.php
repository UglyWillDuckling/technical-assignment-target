<?php

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomer;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomerCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomerQuery;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductCollection;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Override;
use function Lambdish\Phunctional\map;

class MysqlDroppedItemsByCustomerQuery extends AggregateQuery implements DroppedItemsByCustomerQuery
{
    private const array AGGREGATE_FIELDS = ['total'];

    #[Override] protected function aggregateFields(): array
    {
        return self::AGGREGATE_FIELDS;
    }

    #[Override] public function matching(Criteria $criteria): DroppedItemsByCustomerCollection
    {
        return parent::matching($criteria);
    }

    protected function alias(): string {
        return 'd';
    }

    #[Override] protected function select(): string {
        return 'd.sku, d.customer_id, COUNT(d.sku) as total';
    }

    #[Override] protected function groupBy(): string {
        return 'd.sku, d.customer_id';
    }

    protected function createResultCollection(Query $query): DroppedItemsByCustomerCollection
    {
        $items = $query->getResult();

        return new DroppedItemsByCustomerCollection(
            map(fn (array $row) => new DroppedItemsByCustomer(
                    (string)$row['sku'],
                    (string)$row['customer_id'],
                    (int)$row['total']),
                $items),
            (new Paginator($query))->count()
        );
    }
}

