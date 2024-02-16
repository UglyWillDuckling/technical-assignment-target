<?php

declare(strict_types=1);

namespace Acme\Commerce\Cart\Domain;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class ItemRemovedEvent extends DomainEvent
{
  public function __construct(
    private string $id,
    // TODO: revisit this list
    public readonly string $productName,
    public readonly string $sku,
    public readonly string $customerEmail,
    string $eventId = null,
    string $occurredOn = null
  ) {
    parent::__construct($id, $eventId, $occurredOn);
  }

  public static function eventName(): string
  {
    return 'cart_item.removed';
  }

  public static function fromPrimitives(
    string $aggregateId,
    array $body,
    string $eventId,
    string $occurredOn
  ): DomainEvent {
    return new self($aggregateId, $body['productName'], $body['sku'],$body['customer_email'], $eventId, $occurredOn);
  }

  // TODO: see Why this is required
  public function toPrimitives(): array
  {
    return [
      'name' => $this->name,
      'sku' => $this->sku,
      'customer_email' => $this->customerEmail,
    ];
  }
}

