<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class CreateBookChapterContentRequest
{
    #[NotBlank]
    private string $content;

    private ?bool $isPublished = false;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): void
    {
        $this->isPublished = $isPublished;
    }
}
