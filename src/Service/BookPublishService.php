<?php

namespace App\Service;

use App\Model\Author\PublishBookRequest;
use App\Repository\BookRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class BookPublishService
{
    public function __construct(private EntityManagerInterface $em, private BookRepository $bookRepository)
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

    private function setPublicationDate(int $id, ?DateTimeInterface $dateTime): void
    {
        $book = $this->bookRepository->getBookById($id);
        $book->setPublicationDate($dateTime);

        $this->em->flush();
    }
}
