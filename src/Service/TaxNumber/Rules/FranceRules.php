<?php

declare(strict_types=1);

namespace App\Service\TaxNumber\Rules;

use App\Enum\TaxNumber\CodeCountryEnum;
use App\Exception\TaxNumberFormatException;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

// FRYYXXXXXXXXX - for residents of France
#[AutoconfigureTag(name: 'tax_number.format_rules')]
#[AsTaggedItem(index: CodeCountryEnum::France->value)]
final readonly class FranceRules extends AbstractTaxNumberRules
{
    private const int LENGTH = 13;

    /**
     * @throws TaxNumberFormatException
     */
    public function validate(string $taxNumber): void
    {
        $codeCountry = $this->taxCountryHelper->getCodeCountryEnum($taxNumber);
        if ($codeCountry !== CodeCountryEnum::France) {
            throw new TaxNumberFormatException();
        }

        if (strlen($taxNumber) !== self::LENGTH) {
            throw new TaxNumberFormatException(sprintf('The tax number must contain %d characters.', self::LENGTH));
        }

        $onlyLetters = mb_substr($taxNumber, 2, 2);
        if (!ctype_alpha($onlyLetters)) {
            throw new TaxNumberFormatException('The tax Number 2 through 4 must contain only letters.');
        }

        $onlyNumbers = mb_substr($taxNumber, 4);
        if (!ctype_digit($onlyNumbers)) {
            throw new TaxNumberFormatException('The tax number from 4 to 13 must contain only numbers.');
        }
    }
}
