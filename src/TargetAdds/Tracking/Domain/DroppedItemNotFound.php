<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use DomainException;

final class DroppedItemNotFound extends DomainException {
    public function __construct(private readonly string $id) {
        parent::__construct($this->errorMessage());
    }

    public function errorCode(): string
    {
        return 'dropped-item_not_exist';
    }

    private function errorMessage(): string
    {
        return sprintf('The dropped item <%s> does not exist', $this->id);
    }
}
