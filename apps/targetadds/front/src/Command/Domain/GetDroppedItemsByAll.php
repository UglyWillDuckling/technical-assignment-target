<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;
use Acme\Shared\Domain\Utils;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;

use function Lambdish\Phunctional\each;

#[AsCommand(name: 'acme:domain-get_dropped_items-all', description: 'Get Items by all variations',)]
final class GetDroppedItemsByAll extends Command
{
  public function __construct(private readonly DroppedItemRepository $droppedItemRepository) {
    parent::__construct();
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
      $filterTxt = '[{
        "operator": ">",
        "field": "created_at",
        "value": "2024-02-01"
      }
      ]';
      $filterTxtWithSku = '[
         {
            "field": "sku",
            "operator": "IN",
            "value": ["0005", "0011"]
         }
      ]';
      $filterTxtWithTotal = '[
        {
            "field": "total",
            "operator": ">",
            "value": "2"
        }
      ]';

      $limit = 999;
      $order = Order::fromValues('sku', 'asc');

      $filters = Filters::fromValues(Utils::jsonDecode($filterTxtWithSku));
      $criteria = (new Criteria($filters, $order,null, $limit));

//      $matching = $this->droppedItemRepository->matching($criteria);
      $byProducts = $this->droppedItemRepository->byProduct($criteria);
      $byCustomer = $this->droppedItemRepository->byCustomer($criteria);

      each(
          fn(DroppedItem\DroppedItemsByProduct $item) => $output->writeln((string)$item->total),
          $byProducts);
      each(
          fn(DroppedItem\DroppedItemsByCustomer $item) => $output->writeln((string)$item->total),
          $byCustomer);
//      $output->writeln("");
//      $output->writeln("<question>Start</question>");
//      $output->writeln("");
//      $output->writeln("<comment>Matching</comment>");
//      $output->writeln("count: ".$matching->count());
//      $output->writeln("total: ".$matching->countTotal());
//      //      $output->writeln((string)var_dump($matching));
//
//      $output->writeln("");
//      $output->writeln("<comment>Product</comment>");
//      $output->writeln("all: ".$byProducts->countTotal());
//      $output->writeln("count: ".$byProducts->count());
//      $output->writeln("");
//      $output->writeln("<comment>Customer</comment>");
//      $output->writeln((string)$byCustomer->count());
//      $output->writeln((string)$byCustomer->countTotal());
      //      $output->writeln((string)var_dump($matching));
      return Command::SUCCESS;
  }
}

