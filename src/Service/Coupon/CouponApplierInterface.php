<?php

declare(strict_types=1);

namespace App\Service\Coupon;

use App\Entity\Coupon;
use App\Exception\CalculateCostNegativeValueException;

interface CouponApplierInterface
{
    /**
     * @throws CalculateCostNegativeValueException
     */
    public function calculateDiscountedPrice(int $price, Coupon $coupon): int;
}
