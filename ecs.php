<?php

declare(strict_types=1);

use CodelyTv\CodingStyle;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

// TODO: set PSR12 style a
return function (ECSConfig $ecsConfig): void {
	$ecsConfig->paths([__DIR__ . '/apps', __DIR__ . '/src', __DIR__ . '/tests', ]);

	$ecsConfig->sets([CodingStyle::DEFAULT]);

	$ecsConfig->skip([
		__DIR__ . '/apps/targetadds/front/var',
	]);
};
