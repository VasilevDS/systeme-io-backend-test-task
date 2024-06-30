<?php

declare(strict_types=1);

namespace App\Entity;

use App\Doctrine\DBAL\Types\Enum\CouponType;
use App\Enum\CouponTypeEnum;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CouponRepository::class, readOnly: true)]
readonly class Coupon
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    public Uuid $id;

    #[ORM\Column(length: 255), ]
    public string $name;

    #[ORM\Column(type: CouponType::TYPE_NAME, enumType: CouponTypeEnum::class)]
    public CouponTypeEnum $type;

    #[ORM\Column]
    public int $value;

    public function __construct(Uuid $id, string $name, CouponTypeEnum $type, int $value)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }
}
