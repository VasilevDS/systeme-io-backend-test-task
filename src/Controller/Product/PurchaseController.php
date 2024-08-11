<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Dto\Product\ProductPurchaseDto;
use App\Dto\SuccessfulResponse;
use App\Exception\CalculateCostNegativeValueException;
use App\Exception\PaymentProcessorException;
use App\Service\Product\ProductPurchaser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class PurchaseController extends AbstractController
{
    #[Route(path: '/purchase', methods: 'POST')]
    public function purchase(
        #[MapRequestPayload]
        ProductPurchaseDto $requestDto,
        ProductPurchaser $productPurchaser
    ): JsonResponse {
        try {
            $productPurchaser->purchase($requestDto);
        } catch (CalculateCostNegativeValueException|PaymentProcessorException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $this->json(new SuccessfulResponse());
    }
}
