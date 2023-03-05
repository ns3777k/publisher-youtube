<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Entity\Book;
use App\Model\BookChapter as BookChapterModel;
use App\Model\BookChapterTreeResponse;
use App\Repository\BookChapterRepository;

class BookChapterService
{
    public function __construct(private readonly BookChapterRepository $bookChapterRepository)
    {
    }

    public function getChaptersTree(Book $book): BookChapterTreeResponse
    {
        $chapters = $this->bookChapterRepository->findSortedChaptersByBook($book);
        $response = new BookChapterTreeResponse();
        /** @var array<int, BookChapterModel> $index */
        $index = [];

        foreach ($chapters as $chapter) {
            $model = new BookChapterModel($chapter->getId(), $chapter->getTitle(), $chapter->getSlug());
            $index[$chapter->getId()] = $model;

            if (!$chapter->hasParent()) {
                $response->addItem($model);
                continue;
            }

            $parent = $chapter->getParent();
            $index[$parent->getId()]->addItem($model);
        }

        return $response;
    }
}
