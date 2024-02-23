<?php

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filter;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

abstract class AggregateQuery
{
    public function __construct(private readonly EntityManager $entityManager) {}

    abstract protected function aggregateFields(): array;

    public function matching(Criteria $criteria)
    {
        $qb = $this->queryBuilder();

        $qb->from(DroppedItem::class, 'd');
        $qb->select($this->select());
        $qb->groupBy($this->groupBy());

        $this->applyCriteria($qb, $criteria);

        return $this->createResultCollection($qb->getQuery());
    }

    abstract protected function select(): string;

    abstract protected function groupBy(): string;

    abstract protected function createResultCollection(Query $query);

    private function applyCriteria(QueryBuilder $qb, Criteria $criteria): void
    {
        [$criteria, $aggregateFilters] = $this->splitCriteria($criteria);

        $qb->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        /** @var Filter $filter */
        foreach ($aggregateFilters as $filter) {
            $qb->having("{$filter->field()} {$filter->operator()->value} {$filter->value()->value()}");
        }
    }

    private function splitCriteria(Criteria $criteria): array
    {
        $filters = [];
        $aggregateFilters = [];
        foreach ($criteria->plainFilters() as $filter) {
            if(in_array($filter->field()->value(), $this->aggregateFields(), true)) {
                $aggregateFilters[] = $filter;
                continue;
            }
            $filters[] = $filter;
        }

        return [
            new Criteria(new Filters($filters), $criteria->order(), $criteria->offset(), $criteria->limit()),
            $aggregateFilters
        ];
    }

    private function queryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder();
    }
}
