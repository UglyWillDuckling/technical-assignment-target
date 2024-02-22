<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\TargetAdds\Tracking\Domain\DroppedItemNotFound;
use DateTime;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProduct;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByCustomer;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItemsCollection;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\ORM\Tools\Pagination\Paginator;

use function lambdish\phunctional\map;

final class MysqlDroppedItemsRepository extends DoctrineRepository implements DroppedItemRepository
{
    public function save(DroppedItem $droppedItem): void
    {
        $this->persist($droppedItem);
    }

    /**
     * @throws DroppedItemNotFound
     */
    public function search(string $id): DroppedItem {
        $item = $this->repository(DroppedItem::class)->find($id);

        if (!$item) {
            throw new DroppedItemNotFound( $id);
        }

        return $this->repository(DroppedItem::class)->find($id);
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

    public function byProduct(Criteria $criteria): DroppedItem\DroppedItemsByProductCollection
    {
        $query = $this->query('d', 'd.sku, COUNT(d.sku) as total', 'd.sku', $criteria);

        $items = $query->getResult();
        $totalCount = (new Paginator($query))->count();

        return new DroppedItem\DroppedItemsByProductCollection(map(
            fn (array $row) => new DroppedItemsByProduct($row['sku'], (int)$row['total']),
            $items
            ), $totalCount);
    }

    public function byCustomer(Criteria $criteria): DroppedItem\DroppedItemsByCustomerCollection {
        $query = $this->query('d',
            'd.sku,d.customer_id, COUNT(d.sku) as total',
            'd.customer_id,d.sku',
            $criteria
        );

        $items = $query->getResult();
        $totalCount = (new Paginator($query))->count();

        return new DroppedItem\DroppedItemsByCustomerCollection(map(
            fn (array $row) => new DroppedItemsByCustomer(
                (string)$row['sku'], (string)$row['customer_id'], (int)$row['total']
            ),
            $items
        ), $totalCount);
    }

    private function toDoctrineCriteria(Criteria $criteria): DoctrineCriteria
    {
        return DoctrineCriteriaConverter::convert($criteria, [], [
            'created_at' => fn(string $value) => new DateTime($value)]
        );
    }
}

