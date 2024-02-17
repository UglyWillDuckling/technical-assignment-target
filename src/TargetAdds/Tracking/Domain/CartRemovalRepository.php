<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Tracking\Domain;

use Acme\Shared\Domain\Criteria\Criteria;

interface CartRemovalRepository
{
  public function save(BackofficeCourse $course): void;

  public function searchAll(): array;

  public function matching(Criteria $criteria): array;
}
