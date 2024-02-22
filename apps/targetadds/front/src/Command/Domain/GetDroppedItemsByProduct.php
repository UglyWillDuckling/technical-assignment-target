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

#[AsCommand(name: 'acme:domain-get_dropped_byproduct', description: 'Get Items Grouped by product',)]
final class GetDroppedItemsByProduct extends Command
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

//      $filters = Filters::fromValues(Utils::jsonDecode($filterTxt));
      $filters = Filters::fromValues([]);

      $criteria = (new Criteria($filters, Order::none(),null,1));

//      $criteria = Criteria::empty();

//      die(var_dump($filters));
      $collection = $this->droppedItemRepository->byProduct($criteria);

      $output->writeln((string)$collection->count());
      $output->writeln((string)$collection->countTotal());
//      foreach ($result as $item) {
//          echo "\n";
//          echo $item->sku;
//          echo $item->total;
//      }
      return 0;
  }
}
