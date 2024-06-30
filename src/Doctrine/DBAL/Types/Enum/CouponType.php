<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types\Enum;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Override;

final class CouponType extends Type
{
    public const string TYPE_NAME = 'coupon_type';

    #[Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return self::TYPE_NAME;
    }

    #[Override]
    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    #[Override]
    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::TYPE_NAME];
    }
}
