<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Model\Author\PublishBookRequest;
use App\Repository\BookRepository;

class BookPublishService
{
    public function __construct(private readonly BookRepository $bookRepository)
    {
    }

    public function publish(int $id, PublishBookRequest $publishBookRequest): void
    {
        $this->setPublicationDate($id, $publishBookRequest->getDate());
    }

    public function unpublish(int $id): void
    {
        $this->setPublicationDate($id, null);
    }

    private function setPublicationDate(int $id, ?\DateTimeInterface $dateTime): void
    {
        $book = $this->bookRepository->getBookById($id);
        $book->setPublicationDate($dateTime);

        $this->bookRepository->commit();
    }
}
