<?php

declare(strict_types=1);

namespace Acme\Shared\Domain\Criteria;

enum FilterOperator: string
{
	case EQUAL = '=';
	case NOT_EQUAL = '!=';
	case GT = '>';
	case LT = '<';
	case CONTAINS = 'CONTAINS';
	case NOT_CONTAINS = 'NOT_CONTAINS';
	case IN = 'IN';

	public function isContaining(): bool
	{
		return in_array($this->value, [self::CONTAINS->value, self::NOT_CONTAINS->value], true);
	}
}
