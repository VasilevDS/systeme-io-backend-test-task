<?php

declare(strict_types=1);

namespace App\Validation\TaxNumber;

use App\Exception\TaxNumberFormatException;
use App\Service\TaxNumber\Rules\AbstractTaxNumberRules;
use App\Service\TaxNumber\TaxCountryHelper;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Traversable;

class TaxNumberConstraintValidator extends ConstraintValidator
{
    /** @var AbstractTaxNumberRules[] */
    private array $taxNumbersRules;

    public function __construct(
        #[TaggedIterator(tag: 'tax_number.format_rules', indexAttribute: 'key')]
        Traversable $taxNumbersRules,
        private readonly TaxCountryHelper $taxCountryHelper,
    ) {
        $this->taxNumbersRules = iterator_to_array($taxNumbersRules);
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TaxNumberConstraint) {
            throw new UnexpectedTypeException($constraint, TaxNumberConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $codeCountryEnum = $this->taxCountryHelper->getCodeCountryEnum($value);
        if (null === $codeCountryEnum) {
            $this->context
                ->buildViolation('Unable to determine country code.')
                ->addViolation();

            return;
        }

        $rules = $this->taxNumbersRules[$codeCountryEnum->value];

        try {
            $rules->validate($value);
        } catch (TaxNumberFormatException $exception) {
            $this->context
                ->buildViolation($exception->getMessage())
                ->addViolation();
        }
    }
}
