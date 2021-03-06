<?php

declare(strict_types=1);

namespace ClassicCarScaleModels\Orders;

class OrdersService implements IOrdersService
{
    private IOrdersRepository $repository;

    public function __construct(IOrdersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insert(OrdersModel $model): int
    {
        return $this->repository->insert($model->toDto());
    }

    public function update(OrdersModel $model): int
    {
        return $this->repository->update($model->toDto());
    }

    public function get(int $orderNumber): ?OrdersModel
    {
        $dto = $this->repository->get($orderNumber);
        if ($dto === null) {
            return null;
        }

        return new OrdersModel($dto);
    }

    public function getAll(): array
    {
        $dtos = $this->repository->getAll();

        $result = [];
        /* @var OrdersDto $dto */
        foreach ($dtos as $dto) {
            $result[] = new OrdersModel($dto);
        }

        return $result;
    }

    public function delete(int $orderNumber): int
    {
        return $this->repository->delete($orderNumber);
    }

    public function createModel(array $row): ?OrdersModel
    {
        if (empty($row)) {
            return null;
        }

        $dto = new OrdersDto($row);

        return new OrdersModel($dto);
    }
}