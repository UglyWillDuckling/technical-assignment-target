<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Persistence\Doctrine;

use Acme\Shared\Domain\Aggregate\AggregateRoot;
use Acme\Shared\Domain\Criteria\Criteria;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Query as DoctrineQuery;
use function Lambdish\Phunctional\map;

abstract class DoctrineRepository
{
	public function __construct(private readonly EntityManager $entityManager) {}

	protected function entityManager(): EntityManager
	{
		return $this->entityManager;
	}

	protected function persist(AggregateRoot $entity): void
	{
		$this->entityManager()->persist($entity);
		$this->entityManager()->flush($entity);
	}

	protected function remove(AggregateRoot $entity): void
	{
		$this->entityManager()->remove($entity);
		$this->entityManager()->flush($entity);
	}

	/**
	 * @template T of object
	 *
	 * @psalm-param class-string<T> $entityClass
	 *
	 * @psalm-return EntityRepository<T>
	 *
	 * @throws NotSupported
	 */
	protected function repository(string $entityClass): EntityRepository
	{
		return $this->entityManager->getRepository($entityClass);
	}

    protected function query(string $alias, string $select, string $groupBy, Criteria $criteria): DoctrineQuery
    {
        $queryBuilder = $this->queryBuilder($alias, $select);

        $queryBuilder
            ->groupBy($groupBy)
            ->addCriteria(DoctrineCriteriaConverter::convert($criteria));

        return $queryBuilder->getQuery();
    }

    protected function countTotal(array $columns, Criteria $criteria): int
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

    protected function queryBuilder(string $alias, string $select): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository(DroppedItem::class)
            ->createQueryBuilder($alias)
            ->select($select);
    }
}
