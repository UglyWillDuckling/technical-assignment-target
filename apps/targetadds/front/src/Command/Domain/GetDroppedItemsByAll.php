<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;
use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItem\DroppedItemsByProductQuery;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'acme:domain-get_dropped_items-all', description: 'Get Items by all variations', )]
final class GetDroppedItemsByAll extends Command
{
    public function __construct(
        private readonly DroppedItemsByProductQuery $droppedItemsByProductQuery,
        private readonly DroppedItem\DroppedItemsByCustomerQuery $droppedItemsByCustomerQuery,
        private readonly DroppedItemRepository $droppedItemRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = 999;
        $filter = [
            [
                'field' => 'created_at',
                'operator' => '>',
                'value' => '2024-02-01'
            ],
        ];

        $criteria = (new Criteria(Filters::fromValues($filter), Order::fromValues('', 'asc'), null, $limit));

        $byCustomer = $this->droppedItemsByCustomerQuery->matching($criteria);
        $byProduct = $this->droppedItemsByProductQuery->matching($criteria);
        $matching = $this->droppedItemRepository->matching($criteria);

        $output->writeln("");
        $output->writeln("<question>Start</question>");
        $output->writeln("");
        $output->writeln("<comment>Matching</comment>");
        $output->writeln("count: " . $byCustomer->count());
        $output->writeln("total: " . $byCustomer->countTotal());

        return Command::SUCCESS;
    }
}
