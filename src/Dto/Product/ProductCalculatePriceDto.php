<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Validation\EntityExists;
use App\Validation\TaxNumber\TaxNumberConstraint;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ProductCalculatePriceDto
{
    public function __construct(
        #[EntityExists(Product::class)]
        #[NotBlank]
        public string $productId,
        #[TaxNumberConstraint()]
        #[NotBlank]
        public string $taxNumber,
        #[EntityExists(Coupon::class, 'code')]
        public ?string $couponCode = null,
    ) {}
}
