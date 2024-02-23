<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Persistence\Doctrine;

use Acme\Shared\Domain\Aggregate\AggregateRoot;

use Acme\Shared\Domain\Collection;
use Acme\Shared\Domain\Criteria\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\NotSupported;

use function Lambdish\Phunctional\each;

abstract class DoctrineRepository
{
	public function __construct(private readonly EntityManager $entityManager) {}

	protected function entityManager(): EntityManager
	{
		return $this->entityManager;
	}

    /**
     * @template T of AggregateRoot
     *
     * @psalm-param Collection<T> $coll
     *
     */
    public function persistCollection(Collection $coll): void
    {
        $em = $this->entityManager;

        each(fn($item) => $em->persist($item), $coll);

        $em->flush();
    }

	protected function persist(AggregateRoot $entity): void
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}

	protected function remove(AggregateRoot $entity): void
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
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
}
