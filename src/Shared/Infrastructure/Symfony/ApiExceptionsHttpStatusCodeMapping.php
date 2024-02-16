<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Symfony;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Lambdish\Phunctional\get;

final class ApiExceptionsHttpStatusCodeMapping
{
	private const DEFAULT_STATUS_CODE = HttpResponse::HTTP_INTERNAL_SERVER_ERROR;

	private array $exceptions = [
		InvalidArgumentException::class => HttpResponse::HTTP_BAD_REQUEST,
		NotFoundHttpException::class => HttpResponse::HTTP_NOT_FOUND,
	];

	public function register(string $exceptionClass, int $statusCode): void
	{
		$this->exceptions[$exceptionClass] = $statusCode;
	}

	public function statusCodeFor(string $exceptionClass): int
	{
		$statusCode = get($exceptionClass, $this->exceptions, self::DEFAULT_STATUS_CODE);

		if ($statusCode === null) {
			throw new InvalidArgumentException("There are no status code mapping for <$exceptionClass>");
		}

		return $statusCode;
	}
}

