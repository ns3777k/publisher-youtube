<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

class SortContext
{
    private function __construct(private readonly SortPosition $position, private readonly int $nearId)
    {
    }

    public static function fromNeighbours(?int $nextId, ?int $previousId): self
    {
        $position = match (true) {
            null === $previousId && null !== $nextId => SortPosition::AsFirst,
            null !== $previousId && null === $nextId => SortPosition::AsLast,
            default => SortPosition::Between,
        };

        return new self($position, SortPosition::AsLast === $position ? $previousId : $nextId);
    }

    public function getPosition(): SortPosition
    {
        return $this->position;
    }

    public function getNearId(): int
    {
        return $this->nearId;
    }
}
