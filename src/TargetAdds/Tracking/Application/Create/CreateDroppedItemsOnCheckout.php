<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Application\Create;

use Acme\Commerce\Cart\Domain\CheckoutEvent;
use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\TargetAdds\Tracking\Domain\CartRemovalRepository;
use Acme\TargetAdds\Tracking\Domain\CartRemoval;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Ramsey\Uuid\Uuid;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\each;

final readonly class CreateDroppedItemsOnCheckout implements DomainEventSubscriber
{
    public function __construct(
        private DroppedItemRepository $droppedItemRepository,
        private CartRemovalRepository $removalRepo)
    {}

    public static function subscribedTo(): array
    {
        return [CheckoutEvent::class];
    }

    public function __invoke(CheckoutEvent $event): void
    {
        $cartId = $event->cart_id;
        $customerId = $event->customer_id;
        $productSkus = $event->productSkus;

        $removedItems = $this->removalRepo->byCartId($event->cart_id);

        $removedItems = filter(
            fn($cartRemoval) => !in_array($cartRemoval->sku, $productSkus, true), $removedItems);

        // TODO: the droppedItem should have a unique ID Class
        $droppedItems = map(
            fn (CartRemoval $cartRemoval)
                => new DroppedItem(Uuid::uuid4()->toString(), $cartId, $customerId, $cartRemoval->sku), $removedItems);

        $droppedRepository = $this->droppedItemRepository;

        each(static fn (DroppedItem $droppedItem) => $droppedRepository->save($droppedItem), $droppedItems);
    }
}

