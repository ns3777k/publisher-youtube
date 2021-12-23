<?php

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends RuntimeException
{
    public function __construct(private ConstraintViolationListInterface $violations)
    {
        parent::__construct('validation failed');
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
