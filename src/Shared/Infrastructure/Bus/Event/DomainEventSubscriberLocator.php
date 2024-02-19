<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Bus\Event;

use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use Acme\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use RuntimeException;
use Traversable;

use function Lambdish\Phunctional\search;

final class DomainEventSubscriberLocator
{
	private readonly array $mapping;

	public function __construct(Traversable $mapping)
	{
		$this->mapping = iterator_to_array($mapping);
	}

	public function allSubscribedTo(string $eventClass): array
	{
		$formatted = CallableFirstParameterExtractor::forPipedCallables($this->mapping);

		return $formatted[$eventClass];
	}

	public function all(): array
	{
		return $this->mapping;
	}
}
