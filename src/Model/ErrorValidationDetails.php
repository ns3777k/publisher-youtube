<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class ErrorValidationDetails
{
    /**
     * @var ErrorValidationDetailsItem[]
     */
    private array $violations = [];

    public function addViolation(string $field, string $message): void
    {
        $this->violations[] = new ErrorValidationDetailsItem($field, $message);
    }

    /**
     * @return ErrorValidationDetailsItem[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
