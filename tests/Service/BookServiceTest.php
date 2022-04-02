<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookDetails;
use App\Model\BookFormat as BookFormatModel;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Service\Rating;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;

    private BookCategoryRepository $bookCategoryRepository;

    private RatingService $ratingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
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
            ->method('findBooksByCategoryId')
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
        $this->bookRepository->expects($this->once())
            ->method('getById')
            ->with(123)
            ->willReturn($this->createBookEntity());

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
            ->setTitle('Test Book')
            ->setImage('http://localhost/test.png')
            ->setAuthors(['Tester'])
            ->setMeap(false)
            ->setCategories([
                new BookCategoryModel(1, 'Category', 'category'),
            ])
            ->setPublicationDate(1602288000)
            ->setFormats([$format]);

        $this->assertEquals($expected, $this->createBookService()->getBookById(123));
    }

    private function createBookService(): BookService
    {
        return new BookService(
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->ratingService
        );
    }

    private function createBookEntity(): Book
    {
        $category = (new BookCategory())->setTitle('Category')->setSlug('category');
        $this->setEntityId($category, 1);

        $format = (new BookFormat())->setTitle('format')->setDescription('description format')
            ->setComment(null);

        $this->setEntityId($format, 1);

        $join = (new BookToBookFormat())->setPrice(123.55)
            ->setFormat($format)
            ->setDiscountPercent(5);

        $this->setEntityId($join, 1);

        $book = (new Book())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setAuthors(['Tester'])
            ->setImage('http://localhost/test.png')
            ->setCategories(new ArrayCollection([$category]))
            ->setPublicationDate(new DateTimeImmutable('2020-10-10'))
            ->setFormats(new ArrayCollection([$join]));

        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('http://localhost/test.png')
            ->setPublicationDate(1602288000);
    }
}
