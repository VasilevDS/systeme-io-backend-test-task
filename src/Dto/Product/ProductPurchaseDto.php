<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class ProductPurchaseDto extends ProductCalculatePriceDto
{
    #[NotBlank]
    public PaymentProcessorEnum $paymentProcessor;

    public function __construct(
        string $productId,
        string $taxNumber,
        PaymentProcessorEnum $paymentProcessor,
        ?string $couponCode = null,
    ) {
        parent::__construct($productId, $taxNumber, $couponCode);

        $this->paymentProcessor = $paymentProcessor;
    }
}
