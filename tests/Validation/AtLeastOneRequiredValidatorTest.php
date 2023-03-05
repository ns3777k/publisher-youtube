<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Validation;

use App\Validation\AtLeastOneRequired;
use App\Validation\AtLeastOneRequiredValidator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class AtLeastOneRequiredValidatorTest extends ConstraintValidatorTestCase
{
    private PropertyAccessorInterface $propertyAccessor;

    protected function setUp(): void
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        parent::setUp();
    }

    public function testValidateExceptionOnUnexpectedType(): void
    {
        $this->expectException(UnexpectedTypeException::class);

        $this->validator->validate([], new NotNull());
    }

    public function testValidateNoRequired(): void
    {
        $constraint = new AtLeastOneRequired(['nextId']);
        $object = new \stdClass();
        $object->nextId = null;

        $this->validator->validate($object, $constraint);

        $this->buildViolation('At least one of {{ fields }} is required.')
            ->setParameter('{{ fields }}', 'nextId')
            ->atPath('property.path.nextId')
            ->setCode(AtLeastOneRequired::ONE_REQUIRED_ERROR)
            ->assertRaised();
    }

    public function testValidate(): void
    {
        $constraint = new AtLeastOneRequired(['nextId']);
        $object = new \stdClass();
        $object->nextId = 'test';

        $this->validator->validate($object, $constraint);

        $this->assertNoViolation();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new AtLeastOneRequiredValidator($this->propertyAccessor);
    }
}
