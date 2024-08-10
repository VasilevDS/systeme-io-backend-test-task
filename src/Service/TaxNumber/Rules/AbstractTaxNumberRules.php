<?php

declare(strict_types=1);

namespace App\Service\TaxNumber\Rules;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\TaxCountryHelper;

abstract readonly class AbstractTaxNumberRules
{
    public function __construct(
        protected TaxCountryHelper $taxCountryHelper,
    ) {}

    /**
     * @throws TaxNumberFormatException
     */
    abstract public function validate(string $taxNumber): void;
}
