<?php

namespace App\Service;

class SortContext
{
    private function __construct(private SortPosition $position, private int $nearId)
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
