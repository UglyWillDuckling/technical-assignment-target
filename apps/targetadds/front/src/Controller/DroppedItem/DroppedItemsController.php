<?php

namespace Acme\Apps\TargetAdds\Front\Controller\DroppedItem;

use Acme\Shared\Domain\Criteria\Criteria;
use Acme\Shared\Domain\Criteria\Filters;
use Acme\Shared\Domain\Criteria\Order;
use Acme\Shared\Domain\Utils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use function Lambdish\Phunctional\map;

readonly abstract class DroppedItemsController
{
    public function __invoke(Request $request): JsonResponse
    {
        $items = $this->getItems($this->createCriteria($request));

        return new JsonResponse(
            array_merge(['total_count' => $items->countTotal()], ['items' => map($this->itemsMapping(), $items)]),
            200,
            ['Access-Control-Allow-Origin' => '*']
        );
    }

    abstract protected function getItems(Criteria $criteria): iterable;

    abstract protected function itemsMapping(): callable;

    private function createCriteria(Request $request): Criteria
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $filtersString = Utils::jsonDecode($request->query->get('filters') ?? '{}');

        $filters = Filters::fromValues($filtersString);
        $order = Order::fromValues($request->query->get('order_by'), $request->query->get('order'));

        return (new Criteria($filters, $order,
            $offset === null ? null : (int)$offset,
            $limit === null ? null : (int)$limit
        ));
    }
}
