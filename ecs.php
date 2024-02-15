<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
	->withPaths([__DIR__ . '/src', __DIR__ . '/tests', __DIR__ . '/apps'])
	->withRules([
		ArraySyntaxFixer::class,
	])
	->withPreparedSets(psr12: true)
	->withSkip([
		__DIR__ . '/apps/targetadds/front/var',
	]);

