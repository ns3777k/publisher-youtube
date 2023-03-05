<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookChapterTreeResponse;
use App\Model\BookDetails;
use App\Model\BookFormat as BookFormatModel;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookChapterService;
use App\Service\BookService;
use App\Service\Rating;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    private RatingService $ratingService;

    private BookRepository $bookRepository;

    private BookChapterService $bookChapterService;

    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->bookChapterService = $this->createMock(BookChapterService::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }

    public function testGetBooksByCategoryNotFound(): void
    {
        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        $this->createBookService()->getBooksByCategory(130);
    }

    public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findPublishedBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $this->createBookService()->getBooksByCategory(130));
    }

    public function testGetBookById(): void
    {
        $book = $this->createBookEntity();

        $this->bookChapterService->expects($this->once())
            ->method('getChaptersTree')
            ->with($book)
            ->willReturn(new BookChapterTreeResponse());

        $this->bookRepository->expects($this->once())
            ->method('getPublishedById')
            ->with(123)
            ->willReturn($book);

        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(123)
            ->willReturn(new Rating(10, 5.5));

        $format = (new BookFormatModel())
            ->setId(1)
            ->setTitle('format')
            ->setDescription('description format')
            ->setComment(null)
            ->setPrice(123.55)
            ->setDiscountPercent(5);

        $expected = (new BookDetails())->setId(123)
            ->setRating(5.5)
            ->setReviews(10)
            ->setSlug('test-book')
            ->setTitle('Test book')
            ->setImage('http://localhost.png')
            ->setAuthors(['Tester'])
            ->setCategories([
                new BookCategoryModel(1, 'Devices', 'devices'),
            ])
            ->setPublicationDate(1_602_288_000)
            ->setFormats([$format])
            ->setChapters([]);

        $this->assertEquals($expected, $this->createBookService()->getBookById(123));
    }

    private function createBookService(): BookService
    {
        return new BookService(
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->bookChapterService,
            $this->ratingService,
        );
    }

    private function createBookEntity(): Book
    {
        $category = MockUtils::createBookCategory();
        $this->setEntityId($category, 1);

        $format = MockUtils::createBookFormat();
        $this->setEntityId($format, 1);

        $book = MockUtils::createBook()->setCategories(new ArrayCollection([$category]));
        $this->setEntityId($book, 123);

        $join = MockUtils::createBookFormatLink($book, $format);
        $this->setEntityId($join, 1);

        $book->setFormats(new ArrayCollection([$join]));

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test book')
            ->setSlug('test-book')
            ->setAuthors(['Tester'])
            ->setImage('http://localhost.png')
            ->setPublicationDate(1_602_288_000);
    }
}
