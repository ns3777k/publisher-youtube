<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service\Recommendation\Exception;

final class AccessDeniedException extends RecommendationException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('access denied', $previous);
    }
}
