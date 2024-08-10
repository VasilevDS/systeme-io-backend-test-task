<?php

declare(strict_types=1);

namespace App\Entity\Coupon;

use App\Entity\Coupon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class PercentCoupon extends Coupon
{
    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $discountPercent;

    public function __construct(Uuid $id, string $code, int $discountPercent)
    {
        $this->discountPercent = $discountPercent;

        parent::__construct($id, $code);
    }

    public function getDiscountPercent(): int
    {
        return $this->discountPercent;
    }

    public function setDiscountPercent(int $discountPercent): self
    {
        $this->discountPercent = $discountPercent;

        return $this;
    }
}
