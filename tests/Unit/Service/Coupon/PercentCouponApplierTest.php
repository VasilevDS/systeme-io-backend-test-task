<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Coupon;

use App\Entity\Coupon;
use App\Entity\Coupon\PercentCoupon;
use App\Service\Coupon\PercentCouponApplier;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class PercentCouponApplierTest extends TestCase
{
    private PercentCouponApplier $couponApplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->couponApplier = new PercentCouponApplier();
    }

    #[DataProvider('successfulProvider')]
    public function testSuccessful(int $price, Coupon $coupon, int $expectedPrice): void
    {
        $discountedPrice = $this->couponApplier->calculateDiscountedPrice($price, $coupon);

        self::assertEquals($expectedPrice, $discountedPrice);
    }

    public static function successfulProvider(): array
    {
        return [
            [
                200,
                new PercentCoupon(Uuid::v7(), 'code', 50),
                100,
            ],
            [
                28,
                new PercentCoupon(Uuid::v7(), 'code', 10),
                25,
            ],
        ];
    }
}
