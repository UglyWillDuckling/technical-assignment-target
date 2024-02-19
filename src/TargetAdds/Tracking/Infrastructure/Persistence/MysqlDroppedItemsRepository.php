<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;

final class MysqlDroppedItemsRepository extends DoctrineRepository implements DroppedItemRepository
{
	public function save(DroppedItem $droppedItem): void
	{
		$this->persist($droppedItem);
	}

	public function searchAll(): array
	{
		return $this->repository(DroppedItem::class)->findAll();
	}

	public function matching(Criteria $criteria): array
	{
		$doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

		return $this->repository(DroppedItem::class)->matching($doctrineCriteria)->toArray();
	}
}
