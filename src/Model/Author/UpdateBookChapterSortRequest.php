<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model\Author;

use App\Validation\AtLeastOneRequired;
use Symfony\Component\Validator\Constraints\Positive;

#[AtLeastOneRequired(['nextId', 'previousId'])]
class UpdateBookChapterSortRequest
{
    #[Positive]
    private ?int $nextId = null;

    #[Positive]
    private ?int $previousId = null;

    public function getNextId(): ?int
    {
        return $this->nextId;
    }

    public function setNextId(?int $nextId): self
    {
        $this->nextId = $nextId;

        return $this;
    }

    public function getPreviousId(): ?int
    {
        return $this->previousId;
    }

    public function setPreviousId(?int $previousId): self
    {
        $this->previousId = $previousId;

        return $this;
    }
}
