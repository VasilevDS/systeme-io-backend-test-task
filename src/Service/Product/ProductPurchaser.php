<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Dto\Product\ProductPurchaseDto;
use App\Exception\CalculateCostNegativeValueException;
use App\Exception\PaymentProcessorException;
use App\Service\PaymentProcessors\PaymentProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

final readonly class ProductPurchaser
{
    /** @var PaymentProcessorInterface[] */
    private array $paymentProcessors;

    public function __construct(
        #[TaggedIterator(tag: 'payment_processor', indexAttribute: 'key')]
        Traversable $paymentProcessor,
        private ProductPriceCalculator $productPriceCalculator,
    ) {
        $this->paymentProcessors = iterator_to_array($paymentProcessor);
    }

    /**
     * @throws PaymentProcessorException|CalculateCostNegativeValueException
     */
    public function purchase(ProductPurchaseDto $productPurchaseDto): void
    {
        $price = $this->productPriceCalculator->calculate($productPurchaseDto);

        $paymentProcessor = $this->paymentProcessors[$productPurchaseDto->paymentProcessor->value];

        $paymentProcessor->purchase($price);
    }
}
