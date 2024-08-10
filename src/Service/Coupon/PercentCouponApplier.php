<?php

declare(strict_types=1);

namespace App\Service\Coupon;

use App\Entity\Coupon;
use App\Entity\Coupon\PercentCoupon;
use Override;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'coupon.applier')]
#[AsTaggedItem(index: PercentCoupon::class)]
final class PercentCouponApplier implements CouponApplierInterface
{
    #[Override]
    public function calculateDiscountedPrice(int $price, Coupon $coupon): int
    {
        if (!($coupon instanceof PercentCoupon)) {
            throw new RuntimeException(
                sprintf('Expected a coupon %s, but received %s', PercentCoupon::class, get_class($coupon)),
            );
        }

        $discountPercent = $coupon->getDiscountPercent();

        $discount = ceil(($price * $discountPercent) / 100);

        return $price - (int)$discount;
    }
}
