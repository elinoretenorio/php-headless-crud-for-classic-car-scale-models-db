<?php

declare(strict_types=1);

namespace ClassicCarScaleModels\Payments;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;

class PaymentsController 
{
    const ERROR_INVALID_INPUT = "Invalid input";

    private IPaymentsService $service;

    public function __construct(IPaymentsService $service)
    {
        $this->service = $service;        
    }

    public function insert(RequestInterface $request, array $args): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var PaymentsModel $model */
        $model = $this->service->createModel($data);

        $result = $this->service->insert($model);

        return new JsonResponse(["result" => $result]);
    }

    public function update(RequestInterface $request, array $args): ResponseInterface
    {
        $paymentNumber = (int) ($args["payment_number"] ?? 0);
        if ($paymentNumber <= 0) {
            return new JsonResponse(["result" => $paymentNumber, "message" => self::ERROR_INVALID_INPUT]);
        }

        $data = json_decode($request->getBody()->getContents(), true);
        if (empty($data)) {
            $data = $request->getParsedBody();
        }

        /** @var PaymentsModel $model */
        $model = $this->service->createModel($data);
        $model->setPaymentNumber($paymentNumber);

        $result = $this->service->update($model);

        return new JsonResponse(["result" => $result]);
    }

    public function get(RequestInterface $request, array $args): ResponseInterface
    {
        $paymentNumber = (int) ($args["payment_number"] ?? 0);
        if ($paymentNumber <= 0) {
            return new JsonResponse(["result" => $paymentNumber, "message" => self::ERROR_INVALID_INPUT]);
        }

        /** @var PaymentsModel $model */
        $model = $this->service->get($paymentNumber);

        return new JsonResponse(["result" => $model->jsonSerialize()]);
    }

    public function getAll(RequestInterface $request, array $args): ResponseInterface
    {
        $models = $this->service->getAll();

        $result = [];

        /** @var PaymentsModel $model */
        foreach ($models as $model) {
            $result[] = $model->jsonSerialize();
        }

        return new JsonResponse(["result" => $result]);
    }

    public function delete(RequestInterface $request, array $args): ResponseInterface
    {
        $paymentNumber = (int) ($args["payment_number"] ?? 0);
        if ($paymentNumber <= 0) {
            return new JsonResponse(["result" => $paymentNumber, "message" => self::ERROR_INVALID_INPUT]);
        }

        $result = $this->service->delete($paymentNumber);

        return new JsonResponse(["result" => $result]);
    }
}