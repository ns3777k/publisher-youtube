<?php

namespace App\Attribute;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PARAMETER)]
class RequestFile
{
    /**
     * @param string       $field
     * @param Constraint[] $constraints
     */
    public function __construct(private string $field, private array $constraints = [])
    {
    }

    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return Constraint[]
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}
