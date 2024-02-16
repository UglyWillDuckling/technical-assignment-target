<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Symfony;


use function Lambdish\Phunctional\each;

abstract class HttpController
{
  public function __construct(
    ApiExceptionsHttpStatusCodeMapping $exceptionHandler
  ) {
    each(
      fn (int $httpCode, string $exceptionClass) => $exceptionHandler->register($exceptionClass, $httpCode),
      $this->exceptions()
    );
  }

  abstract protected function exceptions(): array;
}
