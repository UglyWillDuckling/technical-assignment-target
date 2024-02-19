<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Aggregate\AggregateRoot;

final class CartRemoval extends AggregateRoot
{
  public function __construct(
    public readonly string $id,
    public readonly string $cart_id,
    public readonly string $sku
  ) {}

  public static function fromPrimitives(array $primitives): self
  {
    return new self(
      $primitives['id'],
      $primitives['cart_id'],
      $primitives['sku']
    );
  }

  public function toPrimitives(): array
  {
    return [
      'id' => $this->id,
      'cart_id' => $this->cart_id,
      'sku' => $this->sku,
    ];
  }
}
