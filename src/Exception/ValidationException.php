<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

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
