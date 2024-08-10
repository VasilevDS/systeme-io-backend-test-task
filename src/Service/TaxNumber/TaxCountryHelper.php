<?php

declare(strict_types=1);

namespace App\Service\TaxNumber;

use App\Enum\TaxNumber\CodeCountryEnum;

class TaxCountryHelper
{
    private const int TAX_GERMANY_IN_PERCENTAGE = 19;
    private const int TAX_ITALY_IN_PERCENTAGE = 22;
    private const int TAX_FRANCE_IN_PERCENTAGE = 20;
    private const int TAX_GREECE_IN_PERCENTAGE = 24;

    public function getCodeCountryEnum(string $taxNumber): ?CodeCountryEnum
    {
        $codeCountry = mb_substr($taxNumber, 0, 2);

        return CodeCountryEnum::tryFrom($codeCountry);
    }

    public function getTaxPercentage(CodeCountryEnum $codeCountryEnum): int
    {
        return match ($codeCountryEnum) {
            CodeCountryEnum::Germany => self::TAX_GERMANY_IN_PERCENTAGE,
            CodeCountryEnum::Italy => self::TAX_ITALY_IN_PERCENTAGE,
            CodeCountryEnum::France => self::TAX_FRANCE_IN_PERCENTAGE,
            CodeCountryEnum::Greece => self::TAX_GREECE_IN_PERCENTAGE,
        };
    }

    public function getTaxPercentageByTaxNumber(string $taxNumber): ?int
    {
        $codeCountry = $this->getCodeCountryEnum($taxNumber);
        if (null === $codeCountry) {
            return null;
        }

        return $this->getTaxPercentage($codeCountry);
    }
}
