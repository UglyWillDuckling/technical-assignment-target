<?php

declare(strict_types=1);

namespace Acme\TargetAdds\Shared\Infrastructure\Doctrine;

use Acme\Shared\Infrastructure\Doctrine\DoctrineEntityManagerFactory;
use Doctrine\ORM\EntityManagerInterface;

final class TargetAddsEntityManagerFactory
{
	private const SCHEMA_PATH = __DIR__ . '/../../../../../etc/databases/targetadds/default.sql';

	public static function create(array $parameters, string $environment): EntityManagerInterface
	{
		$isDevMode = $environment !== 'prod';

		$prefixes = array_merge(
			DoctrinePrefixesSearcher::inPath(__DIR__ . '/../../../../TargetAdds', 'Acme\TargetAdds'),
		);

		$dbalCustomTypesClasses = DbalTypesSearcher::inPath(__DIR__ . '/../../../../TargetAdds', 'TargetAdds');

		return DoctrineEntityManagerFactory::create(
			$parameters,
			$prefixes,
			$isDevMode,
			self::SCHEMA_PATH,
			$dbalCustomTypesClasses
		);
	}
}
