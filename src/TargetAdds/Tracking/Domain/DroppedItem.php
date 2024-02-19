<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Aggregate\AggregateRoot;

final class DroppedItem extends AggregateRoot
{
  public readonly \DateTime $created_at;

  public function __construct(
      // TODO: the ID should be a unique type
    public readonly string $id,
    public readonly string $cart_id,
    public readonly string $customer_id,
    public readonly string $sku
  ) {
      $this->created_at = new \DateTime('now');
  }
}

