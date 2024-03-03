<?php /** @noinspection ALL */

declare(strict_types=1);

namespace Acme\Apps\TargetAdds\Front\Command\Domain;

use Acme\TargetAdds\Tracking\Domain\DroppedItem;
use Acme\TargetAdds\Tracking\Domain\DroppedItemsCollection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\TargetAdds\Tracking\Domain\DroppedItemRepository;
use Faker\Factory;

#[AsCommand(name: 'acme:domain-dummy-create_basic_items', description: 'Create Basic Dummy Dropped Items',)]
final class CreateBasicDummyItems extends Command
{
    public function __construct(private readonly DroppedItemRepository $droppedItemRepository)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();
        $skus = [ '0001', '0002', '0003', '0004', '0005', '0006', '0007', '0008', '0009', '0010'];

        $items = [];
        foreach($skus as $sku) {
            $items[] = new DroppedItem(
                Uuid::uuid4()->toString(),
                (string)random_int(1, 1000),
                $faker->email(),
                $sku
            );
        }

        $coll = new DroppedItemsCollection($items, 1);

        $this->droppedItemRepository->saveCollection($coll);

        return Command::SUCCESS;
    }
}
