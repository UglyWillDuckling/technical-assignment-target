<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Controller\Home;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Acme\Shared\Infrastructure\Symfony\WebController;
use Doctrine\ORM\EntityManager;

use Acme\Shared\Infrastructure\Symfony\ApiExceptionsHttpStatusCodeMapping;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

final class HomeController extends WebController
{
	public function __construct(
		Environment $twig,
		RouterInterface $router,
		RequestStack $requestStack,
		ApiExceptionsHttpStatusCodeMapping $exceptionHandler,
		private readonly EntityManager $entityManager,
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

