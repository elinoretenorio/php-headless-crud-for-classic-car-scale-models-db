<?php

declare(strict_types=1);

namespace ClassicCarScaleModels\Customers;

class CustomersService implements ICustomersService
{
    private ICustomersRepository $repository;

    public function __construct(ICustomersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function insert(CustomersModel $model): int
    {
        return $this->repository->insert($model->toDto());
    }

    public function update(CustomersModel $model): int
    {
        return $this->repository->update($model->toDto());
    }

    public function get(int $customerNumber): ?CustomersModel
    {
        $dto = $this->repository->get($customerNumber);
        if ($dto === null) {
            return null;
        }

        return new CustomersModel($dto);
    }

    public function getAll(): array
    {
        $dtos = $this->repository->getAll();

        $result = [];
        /* @var CustomersDto $dto */
        foreach ($dtos as $dto) {
            $result[] = new CustomersModel($dto);
        }

        return $result;
    }

    public function delete(int $customerNumber): int
    {
        return $this->repository->delete($customerNumber);
    }

    public function createModel(array $row): ?CustomersModel
    {
        if (empty($row)) {
            return null;
        }

        $dto = new CustomersDto($row);

        return new CustomersModel($dto);
    }
}