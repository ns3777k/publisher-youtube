<?php

declare(strict_types=1);

namespace App\Service\Recommendation\Model;

class RecommendationItem
{
    public function __construct(private int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
