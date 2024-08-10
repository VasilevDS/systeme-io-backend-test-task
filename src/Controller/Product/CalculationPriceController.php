<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Dto\Product\PriceResponse;
use App\Dto\Product\ProductCalculatePriceDto;
use App\Exception\CalculateCostNegativeValueException;
use App\Service\Product\ProductPriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class CalculationPriceController extends AbstractController
{
    #[Route(path: '/calculate-price', methods: 'POST')]
    public function calculation(
        #[MapRequestPayload]
        ProductCalculatePriceDto $requestDto,
        ProductPriceCalculator $productPriceCalculator
    ): JsonResponse {
        try {
            $price = $productPriceCalculator->calculate($requestDto);
        } catch (CalculateCostNegativeValueException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $this->json(new PriceResponse($price));
    }
}
