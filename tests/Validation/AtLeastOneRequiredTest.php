<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Validation;

use App\Tests\AbstractTestCase;
use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class AtLeastOneRequiredTest extends AbstractTestCase
{
    public function testEmptyOptionsException(): void
    {
        $this->expectException(ConstraintDefinitionException::class);

        new AtLeastOneRequired();
    }

    public function testEmptyRequiredFieldsException(): void
    {
        $this->expectException(ConstraintDefinitionException::class);

        new AtLeastOneRequired([]);
    }

    public function testShortOptionsToRequiredFields(): void
    {
        $constraint = new AtLeastOneRequired(['nextId', 'previousId']);
        $this->assertEquals(['nextId', 'previousId'], $constraint->requiredFields);
    }
}
