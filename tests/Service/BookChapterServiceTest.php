<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Entity\Book;
use App\Model\BookChapter as BookChapterModel;
use App\Model\BookChapterTreeResponse;
use App\Repository\BookChapterRepository;
use App\Service\BookChapterService;
use App\Tests\AbstractTestCase;
use App\Tests\MockUtils;

class BookChapterServiceTest extends AbstractTestCase
{
    private BookChapterRepository $bookChapterRepository;

    protected function setUp(): void
    {
        $this->bookChapterRepository = $this->createMock(BookChapterRepository::class);

        parent::setUp();
    }

    public function testGetChaptersTree(): void
    {
        $book = new Book();
        $response = new BookChapterTreeResponse([
            new BookChapterModel(1, 'Test chapter', 'test-chapter', [
                new BookChapterModel(2, 'Test chapter', 'test-chapter'),
            ]),
        ]);

        $parentChapter = MockUtils::createBookChapter($book);
        $this->setEntityId($parentChapter, 1);

        $childChapter = MockUtils::createBookChapter($book)->setParent($parentChapter);
        $this->setEntityId($childChapter, 2);

        $this->bookChapterRepository->expects($this->once())
            ->method('findSortedChaptersByBook')
            ->with($book)
            ->willReturn([$parentChapter, $childChapter]);

        $this->assertEquals(
            $response,
            $this->createService()->getChaptersTree($book),
        );
    }

    private function createService(): BookChapterService
    {
        return new BookChapterService($this->bookChapterRepository);
    }
}
