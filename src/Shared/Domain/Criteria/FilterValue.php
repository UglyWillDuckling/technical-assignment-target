<?php

declare(strict_types=1);

namespace Acme\Shared\Domain\Criteria;

readonly class FilterValue
{
    public function __construct(protected string|array $value)
    {
    }

    public function value(): array|string
    {
        return $this->value;
    }
}
