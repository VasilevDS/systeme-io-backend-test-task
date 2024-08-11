<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Product;

use App\Dto\Product\ProductCalculatePriceDto;
use App\Entity\Coupon;
use App\Entity\Coupon\FixedCoupon;
use App\Entity\Coupon\PercentCoupon;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Coupon\FixedCouponApplier;
use App\Service\Coupon\PercentCouponApplier;
use App\Service\Product\ProductPriceCalculator;
use App\Service\TaxNumber\TaxCountryHelper;
use ArrayIterator;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use function PHPUnit\Framework\assertEquals;

final class ProductPriceCalculatorTest extends TestCase
{
    private MockObject&ProductRepository $productRepositoryMock;

    private MockObject&CouponRepository $couponRepositoryMock;

    private ProductPriceCalculator $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productRepositoryMock = $this->createMock(ProductRepository::class);

        $this->couponRepositoryMock = $this->createMock(CouponRepository::class);

        $this->service = new ProductPriceCalculator(
            $this->productRepositoryMock,
            $this->couponRepositoryMock,
            new TaxCountryHelper(),
            new ArrayIterator([
                FixedCoupon::class => new FixedCouponApplier(),
                PercentCoupon::class => new PercentCouponApplier(),
            ]),
        );
    }

    #[DataProvider('successfulProvider')]
    public function testSuccessful(
        ProductCalculatePriceDto $dto,
        Product $product,
        ?Coupon $coupon,
        int $expectedPrice,
    ): void {
        $this->productRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $this->couponRepositoryMock
            ->expects($this->once())
            ->method('findOneByCode')
            ->willReturn($coupon);

        $finalPrice = $this->service->calculate($dto);

        assertEquals($expectedPrice, $finalPrice);
    }

    public static function successfulProvider(): Generator
    {
        $product = new Product(
            Uuid::fromString('01913c04-6b58-7d9a-9fe3-76fb0a4239da'),
            'Product 1',
            1000,
        );

        $fixedCoupon = new FixedCoupon(
            Uuid::fromString('f15c4e8e-6b58-7d9a-9fe3-76fb0a4239da'),
            'F100',
            100,
        );

        yield [
            new ProductCalculatePriceDto(
                '01913c04-6b58-7d9a-9fe3-76fb0a4239da',
                'FRqq123456789',
                'F100',
            ),
            $product,
            $fixedCoupon,
            1080,
        ];

        $percentCoupon = new PercentCoupon(
            Uuid::fromString('f15c4e8e-6b58-7d9a-9fe3-76fb0a4239da'),
            'P15',
            15,
        );

        yield [
            new ProductCalculatePriceDto(
                '01913c04-6b58-7d9a-9fe3-76fb0a4239da',
                'DE123456789',
                'P15',
            ),
            $product,
            $percentCoupon,
            1012,
        ];

        $fixedCoupon = new FixedCoupon(
            Uuid::fromString('f15c4e8e-6b58-7d9a-9fe3-76fb0a4239da'),
            'F744',
            744,
        );

        yield [
            new ProductCalculatePriceDto(
                '01913c04-6b58-7d9a-9fe3-76fb0a4239da',
                'GR123456789',
                'F744',
            ),
            $product,
            $fixedCoupon,
            318,
        ];
    }
}
