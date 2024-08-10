<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use App\Entity\Coupon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class FixedCoupon extends Coupon
{
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $discountValue;

    public function __construct(Uuid $id, string $code, int $discountValue)
    {
        $this->discountValue = $discountValue;

        parent::__construct($id, $code);
    }

    public function getDiscountValue(): int
    {
        return $this->discountValue;
    }

    public function setDiscountValue(int $discountValue): self
    {
        $this->discountValue = $discountValue;

        return $this;
    }
}
