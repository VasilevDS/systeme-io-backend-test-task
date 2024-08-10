<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\TaxNumber\Rules;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\Rules\GreeceRules;
use App\Service\TaxNumber\TaxCountryHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GreeceRulesTest extends TestCase
{
    private GreeceRules $rules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = new GreeceRules(new TaxCountryHelper());
    }

    public function testSuccessful(): void
    {
        $this->expectNotToPerformAssertions();

        $this->rules->validate('GR123456789');
    }

    #[DataProvider('errorProvider')]
    public function testError(string $taxNumber, string $errorMessage): void
    {
        $rules = new GreeceRules(new TaxCountryHelper());
        $this->expectException(TaxNumberFormatException::class);
        $this->expectExceptionMessage($errorMessage);
        $rules->validate($taxNumber);
    }

    public static function errorProvider(): array
    {
        return [
            'Tax number other country' => [
                'DE123456789',
                'Incorrect tax number format.',
            ],
            'Invalid size' => [
                'GR12345678',
                'The tax number must contain 11 characters.',
            ],
            'Invalid format in part numbers' => [
                'GR12345678Q',
                'The tax number from 2 to 10 must contain only numbers.',
            ],
        ];
    }
}
