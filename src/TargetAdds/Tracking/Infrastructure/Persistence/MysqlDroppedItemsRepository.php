<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use DateTime;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItemsCollection;
use Acme\TargetAdds\Tracking\Domain\DroppedItemNotFound;
use Doctrine\Common\Collections\Criteria as DoctrineCriteria;

final class MysqlDroppedItemsRepository extends DoctrineRepository implements DroppedItemRepository
{
    public function save(DroppedItem $droppedItem): void
    {
        $this->persist($droppedItem);
    }

    public function saveCollection(DroppedItemsCollection $coll): void
    {
        $this->persistCollection($coll);
    }

    /**
     * @throws DroppedItemNotFound
     */
    public function search(string $id): DroppedItem {
        $item = $this->repository(DroppedItem::class)->find($id);

        if ($item === NULL) {
            throw new DroppedItemNotFound( $id);
        }

        return $item;
    }

    public function searchAll(): DroppedItemsCollection
    {
        $count = $this->countTotal(['id'], Criteria::empty());
        $items = $this->repository(DroppedItem::class)->findAll();

        return  new DroppedItemsCollection($items, $count);
    }

    public function matching(Criteria $criteria): DroppedItemsCollection
    {
        $doctrineCriteria = $this->toDoctrineCriteria($criteria);

        $count = $this->countTotal(['id'], $criteria);
        $items = $this->repository(DroppedItem::class)->matching($doctrineCriteria)->toArray();

        return new DroppedItemsCollection($items, $count);
    }

    private function toDoctrineCriteria(Criteria $criteria): DoctrineCriteria
    {
        return DoctrineCriteriaConverter::convert($criteria, [], [
            'created_at' => fn(string $value) => new DateTime($value)]
        );
    }

    private function countTotal(array $columns, Criteria $criteria): int
    {
        $alias = 'd';

        if (count($columns) > 1) {
            $columnsSelect = implode(',', map(fn(string $column)  => "$alias.$column", $columns));
            $select = "COUNT(DISTINCT CONCAT($columnsSelect))";
        } else {
            $select = "COUNT(DISTINCT $alias.$columns[0])";
        }

        $qb = $this->queryBuilder($alias, $select);

        $qb->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    private function queryBuilder(string $alias, string $select): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository(DroppedItem::class)
            ->createQueryBuilder($alias)
            ->select($select);
    }
}

