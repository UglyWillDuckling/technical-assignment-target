<?php

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Faker\Factory;

#[AsCommand(name: 'acme:domain-dummy-create_dropped_items', description: 'Create Dummy Dropped Items',)]
final class CreateDummyDroppedItems extends Command
{
  public function __construct(private readonly DroppedItemRepository $droppedItemRepository) {
    parent::__construct();
  }

    protected function execute(InputInterface $input, OutputInterface $output): int
  {
      // TODO: We can make this an Argument
      $howMuch = 1000;

      $faker = Factory::create();

      for ($i = 0; $i <= $howMuch; $i++) {
          $customer_id = (string)random_int(1, 1000);
          $sku = $faker->word();
          $id = Uuid::uuid4()->toString();

           $item = new DroppedItem($id, $customer_id, $sku);
           $this->droppedItemRepository->save($item);
      }

      return Command::SUCCESS;
  }
}
