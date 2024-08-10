<?php

declare(strict_types=1);

namespace App\Validation;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        $isExist = $this->checkEntityExist($constraint->entityClass, $value, $constraint->searchField);

        if (!$isExist) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ entity_class }}', $constraint->entityClass)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    private function checkEntityExist(string $entityClass, string $searchValue, ?string $searchField = null): bool
    {
        try {
            // Check is entity class registered in ORM
            $this->entityManager->getClassMetadata($entityClass);

            if ($searchField === null) {
                $entity = $this->entityManager->find($entityClass, $searchValue);
            } else {
                $entity = $this->entityManager->getRepository($entityClass)->findOneBy([$searchField => $searchValue]);
            }

            if (!$entity instanceof $entityClass) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }
}
