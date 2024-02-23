<?php

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductQuery;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Acme\Shared\Domain\Criteria\Filters;

use function Lambdish\Phunctional\map;

readonly class MysqlDroppedItemsByProductQuery implements DroppedItemsByProductQuery
{
    private const array HAVING_FIELDS = ['total'];

    public function __construct(private EntityManager $entityManager) {}

    public function matching(Criteria $criteria): DroppedItemsByProductCollection
    {
        $qb = $this->queryBuilder();

        $qb->from(DroppedItem::class, 'd');
        $qb->select('d.sku, COUNT(d.sku) as total');
        $qb->groupBy('d.sku');

        $this->applyCriteria($qb, $criteria);

        $query = $qb->getQuery();

        $items = $query->getResult();
        $totalCount = (new Paginator($query))->count();

        return new DroppedItem\DroppedItemsByProductCollection(map(
            fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']),
            $items
        ), $totalCount);
    }

    private function applyCriteria(QueryBuilder $qb, mixed $criteria): void
    {
        [$criteria, $havingFilters] = $this->splitCriteria($criteria);

        $qb->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        /** @var Filter $filter */
        foreach ($havingFilters as $filter) {
            // Example - $qb->having('u.salary >= ?1')
            $qb->having("{$filter->field()} {$filter->operator()->value} {$filter->value()->value()}");
        }
    }

    private function splitCriteria(Criteria $criteria): array
    {
        $filters = [];
        $havingFilters = [];
        foreach ($criteria->plainFilters() as $filter) {
            if(in_array($filter->field()->value(), self::HAVING_FIELDS, true)) {
                $havingFilters[] = $filter;
                continue;
            }
            $filters[] = $filter;
        }

        return [
            new Criteria(new Filters($filters), $criteria->order(), $criteria->offset(), $criteria->limit()),
            $havingFilters
        ];
    }

    private function queryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
