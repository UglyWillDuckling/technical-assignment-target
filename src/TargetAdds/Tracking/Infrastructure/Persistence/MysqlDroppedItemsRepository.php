<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomer;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItemsCollection;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use function lambdish\phunctional\map;

final class MysqlDroppedItemsRepository extends DoctrineRepository implements DroppedItemRepository
{
    public function save(DroppedItem $droppedItem): void
    {
        $this->persist($droppedItem);
    }

    public function searchAll(): DroppedItemsCollection
    {
        $count = $this->countTotal('count(d.id)');
        $items = $this->repository(DroppedItem::class)->findAll();

        return  new DroppedItemsCollection($items, $count);
    }

    public function matching(Criteria $criteria): DroppedItemsCollection
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $totalCount = $this->countTotal('count(d.id)', $criteria);
        $items = $this->repository(DroppedItem::class)->matching($doctrineCriteria)->toArray();

        return new DroppedItemsCollection($items, $totalCount);
    } // TODO: test this method

    public function byProduct(Criteria $criteria): DroppedItem\DroppedItemsByProductCollection
    {
        $totalCount = $this->countTotal('count(distinct d.sku)', $criteria);
        $items = $this->query('d',
            'd.sku, COUNT(d.sku) as total',
            'd.sku',
            $criteria
        )->getResult();

        return new DroppedItem\DroppedItemsByProductCollection(map(
            fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']),
            $items
            ), $totalCount);
    }

    public function byCustomer(Criteria $criteria): DroppedItem\DroppedItemsByCustomerCollection {
        $totalCount = $this->countTotal('count(distinct d.sku)', $criteria);
        $items = $this->query('d',
            'd.sku,d.customer_id, COUNT(d.sku) as total',
            'd.customer_id,d.sku',
            $criteria
        )->getResult();

        return new DroppedItem\DroppedItemsByCustomerCollection(map(
            fn (array $row) => new DroppedItemsByCustomer(
                (string)$row['sku'], (string)$row['customer_id'], (int)$row['total']
            ),
            $items
        ), $totalCount);
    }

    private function query(string $alias, string $select, string $groupBy, Criteria $criteria)
    {
        $queryBuilder = $this->queryBuilder($alias, $select);

        $queryBuilder
            ->groupBy($groupBy)
            ->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        return $queryBuilder->getQuery();
//        return (array)$queryBuilder->getQuery()->getResult();
    }

    private function countTotal(string $select, Criteria $criteria): int
    {
        $queryBuilder = $this
            ->queryBuilder('d', 'count (distinct d.customer_id)')
            ->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    private function queryBuilder(string $alias, string $select): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository(DroppedItem::class)
            ->createQueryBuilder($alias)
            ->select($select);
    }
}

