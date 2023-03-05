<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model\Author;

use Symfony\Component\Validator\Constraints\NotBlank;

class PublishBookRequest
{
    #[NotBlank]
    private \DateTimeInterface $date;

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }
}
