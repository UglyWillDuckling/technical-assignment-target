<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;
use Acme\Shared\Domain\Utils;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;

#[AsCommand(name: 'acme:domain-get_dropped_items', description: 'Get Items Grouped by product',)]
final class GetDroppedItems extends Command
{
  public function __construct(private readonly DroppedItemRepository $droppedItemRepository) {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
      $filterTxt = '[{
        "operator": ">",
        "field": "created_at",
        "value": "2024-02-15"
      }]';

      $filters = Filters::fromValues(Utils::jsonDecode($filterTxt));
      $criteria = (new Criteria($filters, Order::none(),null,1));

      $all = $this->droppedItemRepository->searchAll();
      $byProducts = $this->droppedItemRepository->byProduct($criteria);
      $byCustomers = $this->droppedItemRepository->byCustomer($criteria);

      $collection = $this->droppedItemRepository->matching($criteria);

      $output->writeln("<info>Start</info>");
      $output->writeln((string)$collection->count());
      $output->writeln((string)$collection->countTotal());

      $output->writeln((string)$byProducts->count());
      $output->writeln((string)$byProducts->countTotal());

      $output->writeln((string)$byCustomers->count());
      $output->writeln((string)$byCustomers->countTotal());

      $output->writeln("<info>End</info>");
      return 0;
  }
}
