<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\TaxNumber\Rules;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\Rules\ItalyRules;
use App\Service\TaxNumber\TaxCountryHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ItalyRulesTest extends TestCase
{
    private ItalyRules $rules;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = new ItalyRules(new TaxCountryHelper());
    }

    public function testSuccessful(): void
    {
        $this->expectNotToPerformAssertions();

        $this->rules->validate('IT12345678911');
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
                'IT12345678',
                'The tax number must contain 13 characters.',
            ],
            'Invalid format in part numbers' => [
                'IT1234567891Q',
                'The tax number from 2 to 13 must contain only numbers.',
            ],
        ];
    }
}
