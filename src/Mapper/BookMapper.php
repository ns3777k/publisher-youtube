<?php

namespace App\Mapper;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookToBookFormat;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\Author\BookDetails as AuthorBookDetails;
use App\Model\BookFormat;
use App\Model\BookListItem;

class BookMapper
{
    /**
     * @todo use interface?
     */
    public static function map(Book $book, BookDetails|BookListItem|AuthorBookDetails $model): BookDetails|BookListItem|AuthorBookDetails
    {
        $publicationDate = $book->getPublicationDate();
        if (null !== $publicationDate) {
            $publicationDate = $publicationDate->getTimestamp();
        }

        return $model
            ->setId($book->getId())
            ->setTitle($book->getTitle())
            ->setSlug($book->getSlug())
            ->setImage($book->getImage())
            ->setAuthors($book->getAuthors())
            ->setMeap($book->isMeap())
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
