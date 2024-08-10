<?php

declare(strict_types=1);

namespace App\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class EntityExists extends Constraint
{
    public string $message = 'Entity "{{ entity_class }}" not found by value: "{{ value }}".';

    /**
     * @param class-string $entityClass
     * @param string|null $searchField required for scalar value
     */
    public function __construct(
        public readonly string $entityClass,
        public readonly ?string $searchField = null,
    ) {
        parent::__construct();
    }
}
