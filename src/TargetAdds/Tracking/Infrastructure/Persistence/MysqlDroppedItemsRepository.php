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

use function lambdish\phunctional\map;

final class MysqlDroppedItemsRepository extends DoctrineRepository implements DroppedItemRepository
{
    public function save(DroppedItem $droppedItem): void
    {
        $this->persist($droppedItem);
    }

    public function searchAll(): DroppedItemsCollection
    {
        return  new DroppedItemsCollection($this->repository(DroppedItem::class)->findAll());
    }

    public function matching(Criteria $criteria): DroppedItemsCollection
    {
        $doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

        $items = $this->repository(DroppedItem::class)->matching($doctrineCriteria)->toArray();

        return new DroppedItemsCollection($items);
    }

    public function byProduct(Criteria $criteria = null): array
    {
        $result = $this->query('d',
            'd.sku, COUNT(d.sku) as total',
            'd.sku',
            $criteria ?? Criteria::empty()
        );

        return map(
            fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']),
            $result
        );
    }

    public function byCustomer(Criteria $criteria = null): array {
        $result = $this->query('d',
            'd.sku,d.customer_id, COUNT(d.sku) as total',
            'd.customer_id,d.sku',
            $criteria ?? Criteria::empty()
        );

        return map(
            fn (array $row) => new DroppedItemsByCustomer( $row['sku'], $row['customer_id'], (int)$row['total']),
            $result
        );
    }

    private function query(string $alias, string $select, string $groupBy, Criteria $criteria): array
    {
        $queryBuilder = $this->queryBuilder($alias, $select)
            ->select($select)
            ->groupBy($groupBy)
            ->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        return (array)$queryBuilder->getQuery()->getResult();
    }

    private function queryBuilder(string $alias, string $select): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository(DroppedItem::class)
            ->createQueryBuilder($alias)
            ->select($select);
    }
}

