<?php

namespace App\Service;

use App\Entity\Book;
use App\Model\RecommendedBook;
use App\Model\RecommendedBookListResponse;
use App\Repository\BookRepository;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\RecommendationApiService;

class RecommendationService
{
    private const MAX_DESCRIPTION_LENGTH = 150;

    public function __construct(
        private BookRepository $bookRepository,
        private RecommendationApiService $recommendationApiService)
    {
    }

    public function getRecommendationsByBookId(int $id): RecommendedBookListResponse
    {
        $ids = array_map(
            fn (RecommendationItem $item) => $item->getId(),
            $this->recommendationApiService->getRecommendationsByBookId($id)->getRecommendations()
        );

        return new RecommendedBookListResponse(
            array_map([$this, 'map'], $this->bookRepository->findBooksByIds($ids))
        );
    }

    private function map(Book $book): RecommendedBook
    {
        $description = $book->getDescription();
        $description = strlen($description) > self::MAX_DESCRIPTION_LENGTH
            ? substr($description, 0, self::MAX_DESCRIPTION_LENGTH - 3).'...'
            : $description;

        return (new RecommendedBook())
            ->setId($book->getId())
            ->setImage($book->getImage())
            ->setSlug($book->getSlug())
            ->setTitle($book->getTitle())
            ->setShortDescription($description);
    }
}
