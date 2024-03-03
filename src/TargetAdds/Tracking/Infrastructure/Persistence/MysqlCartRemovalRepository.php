<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Infrastructure\Persistence;

use Acme\TargetAdds\Tracking\Domain\CartRemoval;
use Acme\TargetAdds\Tracking\Domain\CartRemovalRepository;
use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;

use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
use Acme\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;

final class MysqlCartRemovalRepository extends DoctrineRepository implements CartRemovalRepository
{
	public function save(CartRemoval $course): void
	{
		$this->persist($course);
	}

	public function searchAll(): array
	{
		return $this->repository(CartRemoval::class)->findAll();
	}

	public function matching(Criteria $criteria): array
	{
		$doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);

		return $this->repository(CartRemoval::class)->matching($doctrineCriteria)->toArray();
	}

	public function byCartId(string $cartId): array
	{
		$filters =  Filters::fromValues([[
                'field' => 'cart_id',
		       	'operator' => '=',
		       	'value' =>  $cartId
		]]);

		return $this->matching(new Criteria($filters, Order::none(), null, null));
	}
}
