<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Application\Create;

use Acme\Commerce\Cart\Domain\CartItemRemovedEvent;
use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\TargetAdds\Tracking\Domain\CartRemovalRepository;
use Acme\TargetAdds\Tracking\Domain\CartRemoval;
use Ramsey\Uuid\Uuid;

final readonly class CreateItemRemovalOnItemRemoved implements DomainEventSubscriber
{
	public function __construct(private CartRemovalRepository $removalRepo)
	{}

	public static function subscribedTo(): array
	{
		return [CartItemRemovedEvent::class];
	}

	public function __invoke(CartItemRemovedEvent $event): void
	{
		$this->removalRepo->save(new CartRemoval(
			Uuid::uuid4()->toString(),
			$event->cart_id,
			$event->sku
		));
	}
}

