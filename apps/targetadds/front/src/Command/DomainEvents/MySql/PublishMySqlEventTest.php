<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\DomainEvents\MySql;

use Acme\Commerce\Cart\Domain\CartItemRemovedEvent;
use Acme\Commerce\Cart\Domain\CheckoutEvent;
use Acme\Shared\Domain\Bus\Event\EventBus;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'acme:domain-events:mysql:test-publish', description: 'Create a Test domain Event', )]
final class PublishMySqlEventTest extends Command
{
    public function __construct(private readonly EventBus $eventBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();

        $cartId = '1';
        $skuRemoved = '0001';
        $customer_email = $faker->email();

        $this->eventBus->publish(new CartItemRemovedEvent(
            item_id: '1',
            cart_id: $cartId,
            sku: $skuRemoved
        ));

        $this->eventBus->publish(new CheckoutEvent(
            cart_id: $cartId,
            customer_id: '1',
            customer_email: $customer_email,
            productSkus: [$skuRemoved, '0099', '00044']
        ));

        // no dropped items should be created after this

        $newCartId = '2';

        $this->eventBus->publish(new CartItemRemovedEvent(
            item_id: '1',
            cart_id: $newCartId,
            sku: '0005'
        ));
        $this->eventBus->publish(new CheckoutEvent(
            cart_id: $newCartId,
            customer_id: '1',
            customer_email: $customer_email,
            productSkus: ['0099', '00044']
        ));

        return 0;
    }
}
