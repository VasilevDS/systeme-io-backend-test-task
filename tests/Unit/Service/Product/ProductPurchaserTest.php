<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Product;

use App\Dto\Product\ProductPurchaseDto;
use App\Enum\PaymentProcessors\PaymentProcessorEnum;
use App\Service\PaymentProcessors\PayPalPaymentProcessor;
use App\Service\PaymentProcessors\StripePaymentProcessor;
use App\Service\Product\ProductPriceCalculator;
use App\Service\Product\ProductPurchaser;
use ArrayIterator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ProductPurchaserTest extends TestCase
{
    private ProductPriceCalculator&MockObject $productPriceCalculator;
    private ProductPurchaser $productPurchaser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productPriceCalculator = $this->createMock(ProductPriceCalculator::class);

        $this->productPurchaser = new ProductPurchaser(
            new ArrayIterator([
                'paypal' => new PayPalPaymentProcessor(),
                'stripe' => new StripePaymentProcessor(),
            ]),
            $this->productPriceCalculator,
        );
    }

    public function testSuccessful(): void
    {
        $dto = new ProductPurchaseDto(
            '01913c04-6b58-7d9a-9fe3-76fb0a4239da',
            'FRqq123456789',
            PaymentProcessorEnum::Paypal,
            'F100',
        );

        $this->productPriceCalculator
            ->expects($this->once())
            ->method('calculate')
            ->with($dto)
            ->willReturn(1000);

        $this->productPurchaser->purchase($dto);
    }
}
