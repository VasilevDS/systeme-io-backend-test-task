<?php

declare(strict_types = 1);

namespace App\Validation\TaxNumber;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class TaxNumberConstraint extends Constraint
{
}
