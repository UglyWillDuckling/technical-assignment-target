<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Exception;

final class DroppedItemNotFound extends Exception {
    public function __construct(private readonly string $id) {
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'dropped-item_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The dropped item <%s> does not exist', $this->id);
    }
}
