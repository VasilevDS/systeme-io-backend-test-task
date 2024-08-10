<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Coupon;

use App\Entity\Coupon;
use App\Entity\Coupon\FixedCoupon;
use App\Exception\CalculateCostNegativeValueException;
use App\Service\Coupon\FixedCouponApplier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class FixedCouponApplierTest extends TestCase
{
    private FixedCouponApplier $couponApplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->couponApplier = new FixedCouponApplier();
    }

    #[DataProvider('successfulProvider')]
    public function testSuccessful(int $price, Coupon $coupon, int $expectedPrice): void
    {
        $discountedPrice = $this->couponApplier->calculateDiscountedPrice($price, $coupon);

        self::assertEquals($expectedPrice, $discountedPrice);
    }

    public function testError(): void
    {
        $coupon = new FixedCoupon(Uuid::v7(), 'code', 150);

        $this->expectException(CalculateCostNegativeValueException::class);

        $this->expectExceptionMessage('Negative price');

        $this->couponApplier->calculateDiscountedPrice(100, $coupon);
    }

    public static function successfulProvider(): array
    {
        return [
            [
                200,
                new FixedCoupon(Uuid::v7(), 'code', 100),
                100,
            ],

            [
                28,
                new FixedCoupon(Uuid::v7(), 'code', 13),
                15,
            ],
        ];
    }
}
