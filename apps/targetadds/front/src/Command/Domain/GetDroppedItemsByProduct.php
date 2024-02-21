<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

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
//    $result = $this->droppedItemRepository->searchAll();
//      $result = $this->droppedItemRepository->byProduct();
      $result = $this->droppedItemRepository->byCustomer();

      foreach ($result as $item) {
          echo "\n";
          echo $item->sku;
          echo $item->total;
      }

//      var_dump($result);

      return 0;
  }
}
