<?php

declare(strict_types=1);

namespace App\Service\TaxNumber\Rules;

use App\Enum\TaxNumber\CodeCountryEnum;
use App\Exception\TaxNumberFormatException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

// DEXXXXXXXXX - for residents of Germany
#[AutoconfigureTag(name: 'tax_number.format_rules')]
#[AsTaggedItem(index: CodeCountryEnum::Germany->value)]
final readonly class GermanyRules extends AbstractTaxNumberRules
{
    private const int LENGTH = 11;

    /**
     * @throws TaxNumberFormatException
     */
    public function validate(string $taxNumber): void
    {
        $codeCountry = $this->taxCountryHelper->getCodeCountryEnum($taxNumber);
        if ($codeCountry !== CodeCountryEnum::Germany) {
            throw new TaxNumberFormatException();
        }

        if (strlen($taxNumber) !== self::LENGTH) {
            throw new TaxNumberFormatException(sprintf('The tax number must contain %d characters.', self::LENGTH));
        }

        $onlyNumbers = mb_substr($taxNumber, 2);
        if (!ctype_digit($onlyNumbers)) {
            throw new TaxNumberFormatException('The tax number from 2 to 10 must contain only numbers.');
        }
    }
}
