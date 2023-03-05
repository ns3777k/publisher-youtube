<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Validation;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AtLeastOneRequiredValidator extends ConstraintValidator
{
    private readonly PropertyAccessorInterface $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor ?? PropertyAccess::createPropertyAccessor();
    }

    public function validate(mixed $object, Constraint $constraint)
    {
        if (!$constraint instanceof AtLeastOneRequired) {
            throw new UnexpectedTypeException($constraint, AtLeastOneRequired::class);
        }

        $passed = array_filter($constraint->requiredFields, fn (string $required) => null !== $this->propertyAccessor->getValue($object, $required));

        if (!empty($passed)) {
            return;
        }

        $fieldsList = implode(', ', $constraint->requiredFields);

        foreach ($constraint->requiredFields as $required) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ fields }}', $fieldsList)
                ->setCode(AtLeastOneRequired::ONE_REQUIRED_ERROR)
                ->atPath($required)
                ->addViolation();
        }
    }
}
