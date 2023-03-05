<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly BookCategoryRepository $bookCategoryRepository,
        private readonly BookChapterService $bookChapterService,
        private readonly RatingService $ratingService)
    {
    }

    public function getBooksByCategory(int $categoryId): BookListResponse
    {
        if (!$this->bookCategoryRepository->existsById($categoryId)) {
            throw new BookCategoryNotFoundException();
        }

        return new BookListResponse(array_map(
            function (Book $book) {
                $item = new BookListItem();
                BookMapper::map($book, $item);

                return $item;
            },
            $this->bookRepository->findPublishedBooksByCategoryId($categoryId)
        ));
    }

    public function getBookById(int $id): BookDetails
    {
        $book = $this->bookRepository->getPublishedById($id);
        $rating = $this->ratingService->calcReviewRatingForBook($id);
        $details = new BookDetails();

        BookMapper::map($book, $details);

        return $details
            ->setRating($rating->getRating())
            ->setReviews($rating->getTotal())
            ->setFormats(BookMapper::mapFormats($book))
            ->setCategories(BookMapper::mapCategories($book))
            ->setChapters($this->bookChapterService->getChaptersTree($book)->getItems());
    }
}
