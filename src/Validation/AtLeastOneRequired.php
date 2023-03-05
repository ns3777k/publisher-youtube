<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AtLeastOneRequired extends Constraint
{
    /**
     * @var string[]
     */
    public array $requiredFields;

    public string $message = 'At least one of {{ fields }} is required.';

    final public const ONE_REQUIRED_ERROR = '37be695d-786b-4bae-bf45-bd5a1aabf0f7';

    protected const ERROR_NAMES = [
        self::ONE_REQUIRED_ERROR => 'ONE_REQUIRED_ERROR',
    ];

    public function __construct(
        array $options = [],
        array $requiredFields = null,
        string $message = null,
        array $groups = null,
        $payload = null)
    {
        if (!empty($options) && array_is_list($options)) {
            $requiredFields ??= $options;
            $options = [];
        }

        if (empty($requiredFields)) {
            throw new ConstraintDefinitionException('The "requiredFields" of AtLeastOneRequired constraint cannot be empty');
        }

        $options['value'] = $requiredFields;

        parent::__construct($options, $groups, $payload);

        $this->requiredFields = $requiredFields;
        $this->message = $message ?? $this->message;
    }

    public function getRequiredOptions(): array
    {
        return ['requiredFields'];
    }

    public function getDefaultOption(): string
    {
        return 'requiredFields';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
