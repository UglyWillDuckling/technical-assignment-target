<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Doctrine;

use Acme\Shared\Domain\Utils;
use Doctrine\ORM\EntityManager;

use function Lambdish\Phunctional\apply;
use function Lambdish\Phunctional\each;

final readonly class DatabaseConnections
{
  private array $connections;

  public function __construct(iterable $connections)
  {
    $this->connections = Utils::iterableToArray($connections);
  }

  public function clear(): void
  {
    each(fn (EntityManager $entityManager) => $entityManager->clear(), $this->connections);
  }

  public function truncate(): void
  {
    apply(new MySqlDatabaseCleaner(), array_values($this->connections));
  }
}





