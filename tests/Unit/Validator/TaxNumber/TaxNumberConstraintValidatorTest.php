<?php

declare(strict_types = 1);

namespace App\Tests\Unit\Validator\TaxNumber;

use App\Service\TaxNumber\Rules\FranceRules;
use App\Service\TaxNumber\Rules\GermanyRules;
use App\Service\TaxNumber\Rules\GreeceRules;
use App\Service\TaxNumber\Rules\ItalyRules;
use App\Service\TaxNumber\TaxCountryHelper;
use App\Validation\TaxNumber\TaxNumberConstraint;
use App\Validation\TaxNumber\TaxNumberConstraintValidator;
use ArrayIterator;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class TaxNumberConstraintValidatorTest extends ConstraintValidatorTestCase
{
    #[Override]
    protected function createValidator(): TaxNumberConstraintValidator
    {
        $this->constraint = new TaxNumberConstraint();

        $helper = new TaxCountryHelper();

        return new TaxNumberConstraintValidator(
            new ArrayIterator([
                'DE' => new GermanyRules($helper),
                'GR' => new GreeceRules($helper),
                'FR' => new FranceRules($helper),
                'IT' => new ItalyRules($helper),
            ]),
            new TaxCountryHelper(),
        );
    }

    #[DataProvider('successfulProvider')]
    public function testSuccessful(string $taxNumber): void
    {
        $this->validator->validate($taxNumber, $this->constraint);

        $this->assertNoViolation();
    }

    #[DataProvider('errorProvider')]
    public function testError(string $taxNumber, string $message): void
    {
        $this->validator->validate($taxNumber, $this->constraint);

        $violations = $this->context->getViolations();

        self::assertCount(1, $violations);
        self::assertSame($message, $violations->get(0)->getMessage());
    }

    public static function successfulProvider(): array
    {
        return [
            ['DE123456789'],
            ['GR123456789'],
            ['IT12345678900'],
            ['FRxx123456789'],
        ];
    }

    public static function errorProvider(): array
    {
        return [
            'invalidCodeCountry' => [
                'XX123456789',
                'Unable to determine country code.',
            ],
            'lengthError' => [
                'DE1234567890',
                'The tax number must contain 11 characters.',
            ],
        ];
    }
}
