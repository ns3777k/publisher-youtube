<?php

namespace App\Service\Recommendation\Exception;

use Exception;
use Throwable;

class RecommendationException extends Exception
{
    public function __construct(string $message = '', ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
