<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\TaxNumber\Rules;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\Rules\FranceRules;
use App\Service\TaxNumber\TaxCountryHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FranceRulesTest extends TestCase
{
    private FranceRules $rules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = new FranceRules(new TaxCountryHelper());
    }

    public function testSuccessful(): void
    {
        $this->expectNotToPerformAssertions();

        $this->rules->validate('FRqq123456789');
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
                'IT123456789',
                'Incorrect tax number format.',
            ],
            'Invalid size' => [
                'FRqq1234567890',
                'The tax number must contain 13 characters.',
            ],
            'Invalid format in part letters' => [
                'FR00123456789',
                'The tax Number 2 through 4 must contain only letters.',
            ],
            'Invalid format in part numbers' => [
                'FRqq123456XXX',
                'The tax number from 4 to 13 must contain only numbers.',
            ],
        ];
    }
}
