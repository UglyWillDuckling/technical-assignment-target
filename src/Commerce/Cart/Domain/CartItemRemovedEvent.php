<?php

declare(strict_types=1);

namespace Acme\Commerce\Cart\Domain;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class CartItemRemovedEvent extends DomainEvent
{
  public function __construct(
      public readonly string $item_id,
    public readonly string $cart_id,
    public readonly string $customer_id,
    public readonly string $sku,
    string $eventId = null,
    string $occurredOn = null
  ) {
    parent::__construct($this->item_id, $eventId, $occurredOn);
  }

  public static function eventName(): string
  {
    return 'cart-item.removed';
  }

  public static function fromPrimitives(
    string $aggregateId,
    array $body,
    string $eventId,
    string $occurredOn
  ): DomainEvent {
    return new self(
      (string)$body['item_id'],
      (string)$body['cart_id'],
      (string)$body['customer_id'],
      $body['sku'], $eventId, $occurredOn);
  }

  public function toPrimitives(): array
  {
    return [
      'item_id' => $this->item_id,
      'cart_id' => $this->cart_id,
      'customer_id' => $this->customer_id,
      'sku' => $this->sku,
    ];
  }
}
