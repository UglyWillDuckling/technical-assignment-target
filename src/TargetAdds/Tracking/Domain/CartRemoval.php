<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Aggregate\AggregateRoot;
use DateTime;

final class CartRemoval extends AggregateRoot
{
    public readonly DateTime $created_at;

    public function __construct(
        public readonly string $id,
        public readonly string $cart_id,
        public readonly string $sku
    ) {
        $this->created_at = new DateTime('now');
    }

    public static function fromPrimitives(array $primitives): self
    {
        return new self(
            (string)$primitives['id'],
            (string)$primitives['cart_id'],
            (string)$primitives['sku']
        );
    }

    public function toPrimitives(): array
    {
        return [
          'id' => $this->id,
          'cart_id' => $this->cart_id,
          'sku' => $this->sku
        ];
    }
}
