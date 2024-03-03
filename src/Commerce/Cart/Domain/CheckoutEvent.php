<?php

declare(strict_types=1);

namespace Acme\Commerce\Cart\Domain;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class CheckoutEvent extends DomainEvent
{
  public function __construct(
    public readonly string $cart_id,
    public readonly string $customer_id,
    public readonly string $customer_email,
    public readonly array $productSkus,
    string $eventId = null,
    string $occurredOn = null
  ) {
    parent::__construct($cart_id, $eventId, $occurredOn);
  }

  public static function eventName(): string
  {
    return 'commerce.checkout';
  }

  public static function fromPrimitives(
    string $aggregateId,
    array $body,
    string $eventId,
    string $occurredOn
  ): DomainEvent {
    return new self(
      (string)$body['cart_id'],
      (string)$body['customer_id'],
      (string)$body['customer_email'],
      $body['productSkus'],
      $eventId,
      $occurredOn
    );
  }

  public function toPrimitives(): array
  {
    return [
      'cart_id' => $this->cart_id,
      'customer_id' => $this->customer_id,
      'customer_email' => $this->customer_email,
      'productSkus' => $this->productSkus,
    ];
  }
}
