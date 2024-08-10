<?php

declare(strict_types=1);

namespace App\Service\Coupon;

use App\Entity\Coupon;
use App\Entity\Coupon\FixedCoupon;
use App\Exception\CalculateCostNegativeValueException;
use Override;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'coupon.applier')]
#[AsTaggedItem(index: FixedCoupon::class)]
final class FixedCouponApplier implements CouponApplierInterface
{
    #[Override]
    public function calculateDiscountedPrice(int $price, Coupon $coupon): int
    {
        if (!($coupon instanceof FixedCoupon)) {
            throw new RuntimeException(
                sprintf('Expected a coupon %s, but received %s', FixedCoupon::class, get_class($coupon)),
            );
        }

        $discountValue = $coupon->getDiscountValue();

        $discountedPrice =  $price - $discountValue;

        if ($discountedPrice < 0) {
            throw new CalculateCostNegativeValueException();
        }

        return $discountedPrice;
    }
}
