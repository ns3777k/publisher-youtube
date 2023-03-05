<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Entity\User;
use App\Exception\BookAlreadyExistsException;
use App\Model\Author\BookDetails;
use App\Model\Author\BookFormatOptions;
use App\Model\Author\BookListItem;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\BookCategory;
use App\Model\BookFormat;
use App\Model\IdResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookFormatRepository;
use App\Repository\BookRepository;
use App\Service\AuthorBookService;
use App\Service\UploadService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

class AuthorBookServiceTest extends AbstractTestCase
{
    private BookRepository $bookRepository;

    private BookFormatRepository $bookFormatRepository;

    private BookCategoryRepository $bookCategoryRepository;

    private SluggerInterface $slugger;

    private UploadService $uploadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookFormatRepository = $this->createMock(BookFormatRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->slugger = $this->createMock(SluggerInterface::class);
        $this->uploadService = $this->createMock(UploadService::class);
    }

    public function testUploadCover(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $book = (new Book())->setImage(null);
        $this->setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->uploadService->expects($this->once())
            ->method('uploadBookFile')
            ->with(1, $file)
            ->willReturn('http://localhost/new.jpg');

        $this->assertEquals(
            new UploadCoverResponse('http://localhost/new.jpg'),
            $this->createService()->uploadCover(1, $file),
        );
    }

    public function testUploadCoverRemoveOld(): void
    {
        $file = new UploadedFile('path', 'field', null, UPLOAD_ERR_NO_FILE, true);
        $book = (new Book())->setImage('http://localhost/old.png');
        $this->setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->uploadService->expects($this->once())
            ->method('uploadBookFile')
            ->with(1, $file)
            ->willReturn('http://localhost/new.jpg');

        $this->uploadService->expects($this->once())
            ->method('deleteBookFile')
            ->with(1, 'old.png');

        $this->assertEquals(
            new UploadCoverResponse('http://localhost/new.jpg'),
            $this->createService()->uploadCover(1, $file),
        );
    }

    public function testDeleteBook(): void
    {
        $book = new Book();

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('removeAndCommit')
            ->with($book);

        $this->createService()->deleteBook(1);
    }

    public function testGetBook(): void
    {
        $category = MockUtils::createBookCategory();
        $this->setEntityId($category, 1);

        $format = MockUtils::createBookFormat();
        $this->setEntityId($format, 1);

        $book = MockUtils::createBook()->setCategories(new ArrayCollection([$category]));
        $bookLink = MockUtils::createBookFormatLink($book, $format);
        $book->setFormats(new ArrayCollection([$bookLink]));

        $this->setEntityId($book, 1);

        $bookDetails = (new BookDetails())
            ->setId(1)
            ->setTitle('Test book')->setSlug('test-book')
            ->setImage('http://localhost.png')
            ->setIsbn('123321')
            ->setDescription('test')
            ->setPublicationDate(1_602_288_000)
            ->setAuthors(['Tester'])
            ->setCategories([
                new BookCategory(1, 'Devices', 'devices'),
            ])
            ->setFormats([
                (new BookFormat())->setId(1)->setTitle('format')
                    ->setDescription('description format')
                    ->setComment(null)
                    ->setPrice(123.55)
                    ->setDiscountPercent(5),
            ]);

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->assertEquals($bookDetails, $this->createService()->getBook(1));
    }

    public function testGetBooks(): void
    {
        $user = new User();
        $book = MockUtils::createBook();
        $this->setEntityId($book, 1);

        $this->bookRepository->expects($this->once())
            ->method('findUserBooks')
            ->with($user)
            ->willReturn([$book]);

        $bookItem = (new BookListItem())->setId(1)
            ->setImage('http://localhost.png')
            ->setTitle('Test book')
            ->setSlug('test-book');

        $this->assertEquals(
            new BookListResponse([$bookItem]),
            $this->createService()->getBooks($user),
        );
    }

    public function testCreateBook(): void
    {
        $payload = new CreateBookRequest();
        $payload->setTitle('New Book');
        $user = new User();

        $expectedBook = (new Book())->setTitle('New Book')
            ->setSlug('new-book')
            ->setUser($user);

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('New Book')
            ->willReturn(new UnicodeString('new-book'));

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('new-book')
            ->willReturn(false);

        $this->bookRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedBook)
            ->will($this->returnCallback(function (Book $book) {
                $this->setEntityId($book, 111);
            }));

        $this->assertEquals(new IdResponse(111), $this->createService()->createBook($payload, $user));
    }

    public function testCreateBookSlugExistsException(): void
    {
        $this->expectException(BookAlreadyExistsException::class);

        $payload = new CreateBookRequest();
        $payload->setTitle('New Book');
        $user = new User();

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('New Book')
            ->willReturn(new UnicodeString('new-book'));

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('new-book')
            ->willReturn(true);

        $this->assertEquals(new IdResponse(111), $this->createService()->createBook($payload, $user));
    }

    public function testUpdateBookExceptionOnDuplicateSlug(): void
    {
        $this->expectException(BookAlreadyExistsException::class);

        $book = new Book();
        $payload = (new UpdateBookRequest())->setTitle('Old');

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('Old')
            ->willReturn(new UnicodeString('old'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('old')
            ->willReturn(true);

        $this->createService()->updateBook(1, $payload);
    }

    public function testUpdateBook(): void
    {
        $book = new Book();
        $bookToBookFormat = new BookToBookFormat();
        $book->setFormats(new ArrayCollection([$bookToBookFormat]));

        $category = MockUtils::createBookCategory();
        $this->setEntityId($category, 1);

        $format = MockUtils::createBookFormat();
        $this->setEntityId($format, 1);

        $newBookToBookFormat = (new BookToBookFormat())
            ->setBook($book)->setFormat($format)
            ->setPrice(123.5)->setDiscountPercent(5);

        $payload = (new UpdateBookRequest())->setTitle('Old')->setAuthors(['Tester'])
            ->setIsbn('isbn')
            ->setCategories([1])
            ->setFormats([
                (new BookFormatOptions())->setId(1)->setPrice(123.5)->setDiscountPercent(5),
            ])
            ->setDescription('description');

        $this->slugger->expects($this->once())
            ->method('slug')
            ->with('Old')
            ->willReturn(new UnicodeString('old'));

        $this->bookRepository->expects($this->once())
            ->method('getBookById')
            ->with(1)
            ->willReturn($book);

        $this->bookRepository->expects($this->once())
            ->method('existsBySlug')
            ->with('old')
            ->willReturn(false);

        $this->bookCategoryRepository->expects($this->once())
            ->method('findBookCategoriesByIds')
            ->with([1])
            ->willReturn([$category]);

        $this->bookFormatRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($format);

        $this->bookRepository->expects($this->once())
            ->method('saveBookFormatReference')
            ->with($newBookToBookFormat);

        $this->bookRepository->expects($this->once())
            ->method('removeBookFormatReference')
            ->with($bookToBookFormat);

        $this->bookRepository->expects($this->once())
            ->method('commit');

        $this->createService()->updateBook(1, $payload);
    }

    private function createService(): AuthorBookService
    {
        return new AuthorBookService(
            $this->bookRepository, $this->bookFormatRepository, $this->bookCategoryRepository,
            $this->slugger, $this->uploadService,
        );
    }
}
