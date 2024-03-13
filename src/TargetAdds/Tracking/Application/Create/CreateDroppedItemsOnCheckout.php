<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Application\Create;

use Acme\Commerce\Cart\Domain\CheckoutEvent;
use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\TargetAdds\Tracking\Domain\CartRemoval;
use Acme\TargetAdds\Tracking\Domain\CartRemovalRepository;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Ramsey\Uuid\Uuid;

use function Lambdish\Phunctional\each;
use function Lambdish\Phunctional\filter;
use function Lambdish\Phunctional\map;

final readonly class CreateDroppedItemsOnCheckout implements DomainEventSubscriber
{
    public function __construct(
        private DroppedItemRepository $droppedItemRepository,
        private CartRemovalRepository $removalRepo
    ) {
    }

    public static function subscribedTo(): array
    {
        return [CheckoutEvent::class];
    }

    public function __invoke(CheckoutEvent $event): void
    {
        $droppedRepository = $this->droppedItemRepository;
        $customerId = $event->customer_id;
        $customer_email = $event->customer_email;
        $productSkus = $event->productSkus;

        $removedItems = $this->removalRepo->byCartId($event->cart_id);

        $removedItems = filter(
            fn (CartRemoval $cartRemoval) => !in_array($cartRemoval->sku, $productSkus, true),
            $removedItems
        );

        $droppedItems = map(
            fn (CartRemoval $cartRemoval)
                => new DroppedItem(Uuid::uuid4()->toString(), $customerId, $customer_email, $cartRemoval->sku),
            $removedItems
        );

        each(fn (DroppedItem $droppedItem) => $droppedRepository->save($droppedItem), $droppedItems);
    }
}
