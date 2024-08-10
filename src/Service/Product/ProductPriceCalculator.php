<?php

declare(strict_types=1);

namespace App\Service\Product;

use App\Dto\Product\ProductCalculatePriceDto;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\CalculateCostNegativeValueException;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\Coupon\CouponApplierInterface;
use App\Service\TaxNumber\TaxCountryHelper;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

final readonly class ProductPriceCalculator
{
    /** @var CouponApplierInterface[] */
    private array $couponAppliers;

    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxCountryHelper $taxCountryHelper,
        #[TaggedIterator(tag: 'coupon.applier', indexAttribute: 'key')]
        Traversable $couponAppliers,
    ) {
        $this->couponAppliers = iterator_to_array($couponAppliers);
    }

    /**
     * @throws CalculateCostNegativeValueException
     */
    public function calculate(ProductCalculatePriceDto $productCalculatePriceDto): int
    {
        /** @var Product $product */
        $product = $this->productRepository->find($productCalculatePriceDto->productId);
        $productPrice = $product->getPrice();

        $coupon = null;
        if ($productCalculatePriceDto->couponCode !== null) {
            $coupon = $this->couponRepository->findOneByCode($productCalculatePriceDto->couponCode);
        }

        $discountedPrice = $this->applyCoupon($productPrice, $coupon);

        return $this->applyTax($discountedPrice, $productCalculatePriceDto->taxNumber);
    }

    /**
     * @throws CalculateCostNegativeValueException
     */
    private function applyCoupon(int $productPrice, ?Coupon $coupon): int
    {
        if ($coupon === null) {
            return $productPrice;
        }

        $couponApplier = $this->couponAppliers[get_class($coupon)];

        return $couponApplier->calculateDiscountedPrice($productPrice, $coupon);
    }

    private function applyTax(int $productPrice, string $taxNumber): int
    {
        $taxPercentage= $this->taxCountryHelper->getTaxPercentageByTaxNumber($taxNumber);

        $tax = ceil(($productPrice * $taxPercentage) / 100);

        return $productPrice + (int)$tax;
    }
}
