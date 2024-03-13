<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;
use Acme\Shared\Domain\Utils;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'acme:domain-get_dropped_byproduct', description: 'Get Items Grouped by product', )]
final class GetDroppedItemsByProduct extends Command
{
    public function __construct(
        private readonly DroppedItemsByProductQuery $droppedItemsByProductQuery
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filterTxt = '[{
        "operator": ">",
        "field": "created_at",
        "value": "2024-02-15"
        },
        {
            "field": "total",
            "operator": ">",
            "value": "11"
          }
      ]';

        $filters = Filters::fromValues(Utils::jsonDecode($filterTxt));
        $criteria = (new Criteria($filters, Order::none(), null, 1));

        $collection = $this->droppedItemsByProductQuery->matching($criteria);

        $output->writeln((string)$collection->count());
        $output->writeln((string)$collection->countTotal());
        return 0;
    }
}
