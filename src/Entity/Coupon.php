<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Coupon\FixedCoupon;
use App\Entity\Coupon\PercentCoupon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: Types::STRING)]
#[ORM\DiscriminatorMap(['fixed' => FixedCoupon::class, 'percent' => PercentCoupon::class])]
abstract class Coupon
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    protected Uuid $id;

    #[ORM\Column(length: 255, unique: true)]
    protected string $code;

    public function __construct(Uuid $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
