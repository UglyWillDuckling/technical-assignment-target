<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Controller\Home;

use Acme\Shared\Infrastructure\Symfony\WebController;
use Acme\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use Acme\TargetAdds\Tracking\Domain\CartRemovalRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment as TwigEnv;

final class HomeController extends WebController
{
	public function __construct(
		TwigEnv $twig,
		RouterInterface $router,
		RequestStack $requestStack,
		ApiExceptionsHttpStatusCodeMapping $exceptionHandler,
		private readonly CartRemovalRepository $repo,
	) {
		parent::__construct($twig, $router, $requestStack, $exceptionHandler);
	}

	public function __invoke(Request $request): Response
	{
		return $this->render('pages/home.html.twig', [
			'title' => 'Welcome',
			'description' => 'Home Page',
		]);
	}

	protected function exceptions(): array
	{
		return [];
	}
}

