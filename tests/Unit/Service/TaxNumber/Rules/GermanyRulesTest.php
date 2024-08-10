<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\TaxNumber\Rules;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\Rules\GermanyRules;
use App\Service\TaxNumber\TaxCountryHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GermanyRulesTest extends TestCase
{
    private GermanyRules $rules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = new GermanyRules(new TaxCountryHelper());
    }

    public function testSuccessful(): void
    {
        $this->expectNotToPerformAssertions();

        $this->rules->validate('DE123456789');
    }

    #[DataProvider('errorProvider')]
    public function testError(string $taxNumber, string $errorMessage): void
    {
        $this->expectException(TaxNumberFormatException::class);

        $this->expectExceptionMessage($errorMessage);

        $this->rules->validate($taxNumber);
    }

    public static function errorProvider(): array
    {
        return [
            'Tax number other country' => [
                'GE123456789',
                'Incorrect tax number format.',
            ],
            'Invalid size' => [
                'DE12345678',
                'The tax number must contain 11 characters.',
            ],
            'Invalid format in part numbers' => [
                'DE12345678X',
                'The tax number from 2 to 10 must contain only numbers.',
            ],
        ];
    }
}
