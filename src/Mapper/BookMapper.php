<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Mapper;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Model\BaseBookDetails;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookFormat;

class BookMapper
{
    public static function map(Book $book, BaseBookDetails $model): void
    {
        $publicationDate = $book->getPublicationDate();
        if (null !== $publicationDate) {
            $publicationDate = $publicationDate->getTimestamp();
        }

        $model
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setAuthors($book->getAuthors())
            ->setPublicationDate($publicationDate);
    }

    public static function mapCategories(Book $book): array
    {
        return $book->getCategories()
            ->map(fn (BookCategory $bookCategory) => new BookCategoryModel(
                $bookCategory->getId(), $bookCategory->getTitle(), $bookCategory->getSlug()
            ))
            ->toArray();
    }

    /**
     * @return BookFormat[]
     */
    public static function mapFormats(Book $book): array
    {
        return $book->getFormats()
            ->map(fn (BookToBookFormat $formatJoin) => (new BookFormat())
                ->setId($formatJoin->getFormat()->getId())
                ->setTitle($formatJoin->getFormat()->getTitle())
                ->setDescription($formatJoin->getFormat()->getDescription())
                ->setComment($formatJoin->getFormat()->getComment())
                ->setPrice($formatJoin->getPrice())
                ->setDiscountPercent($formatJoin->getDiscountPercent()
                ))
            ->toArray();
    }
}
