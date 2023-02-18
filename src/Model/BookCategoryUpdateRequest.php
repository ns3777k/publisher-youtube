<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

use Symfony\Component\Validator\Constraints\NotBlank;

class BookCategoryUpdateRequest
{
    #[NotBlank]
    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
