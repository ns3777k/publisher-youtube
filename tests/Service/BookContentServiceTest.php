<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Entity\BookChapter;
use App\Entity\BookContent;
use App\Exception\BookChapterContentNotFoundException;
use App\Exception\BookChapterNotFoundException;
use App\Model\Author\CreateBookChapterContentRequest;
use App\Model\BookChapterContent;
use App\Model\BookChapterContentPage;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookContentRepository;
use App\Service\BookContentService;
use App\Tests\AbstractTestCase;

class BookContentServiceTest extends AbstractTestCase
{
    private const PER_PAGE = 30;

    private BookChapterRepository $bookChapterRepository;
    private BookContentRepository $bookContentRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookChapterRepository = $this->createMock(BookChapterRepository::class);
        $this->bookContentRepository = $this->createMock(BookContentRepository::class);
    }

    public function testCreateContentException(): void
    {
        $this->expectException(BookChapterNotFoundException::class);

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterNotFoundException());

        $this->createService()->createContent(new CreateBookChapterContentRequest(), 1);
    }

    public function testCreateContent(): void
    {
        $payload = new CreateBookChapterContentRequest();
        $payload->setContent('testing');
        $payload->setIsPublished(true);

        $chapter = new BookChapter();
        $expectedContent = (new BookContent())
            ->setContent('testing')
            ->setIsPublished(true)
            ->setChapter($chapter);

        $this->bookChapterRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($chapter);

        $this->bookContentRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedContent)
            ->will($this->returnCallback(function (BookContent $content) {
                $this->setEntityId($content, 2);
            }));

        $this->assertEquals(new IdResponse(2), $this->createService()->createContent($payload, 1));
    }

    public function testUpdateContentException(): void
    {
        $this->expectException(BookChapterContentNotFoundException::class);

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterContentNotFoundException());

        $this->createService()->updateContent(new CreateBookChapterContentRequest(), 1);
    }

    public function testUpdateContent(): void
    {
        $payload = new CreateBookChapterContentRequest();
        $payload->setContent('initial');
        $payload->setIsPublished(false);

        $chapter = new BookChapter();
        $content = (new BookContent())->setChapter($chapter);

        $expectedContent = (new BookContent())
            ->setContent('initial')
            ->setIsPublished(false)
            ->setChapter($chapter);

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(2)
            ->willReturn($content);

        $this->bookContentRepository->expects($this->once())
            ->method('saveAndCommit')
            ->with($expectedContent);

        $this->createService()->updateContent($payload, 2);
    }

    public function testDeleteContentException(): void
    {
        $this->expectException(BookChapterContentNotFoundException::class);

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willThrowException(new BookChapterContentNotFoundException());

        $this->createService()->deleteContent(1);
    }

    public function testDeleteContent(): void
    {
        $content = new BookContent();

        $this->bookContentRepository->expects($this->once())
            ->method('getById')
            ->with(1)
            ->willReturn($content);

        $this->bookContentRepository->expects($this->once())
            ->method('removeAndCommit')
            ->with($content);

        $this->createService()->deleteContent(1);
    }

    public function testGetAllContent(): void
    {
        $this->testGetContent(false);
    }

    public function testGetPublishedContent(): void
    {
        $this->testGetContent(true);
    }

    private function testGetContent(bool $onlyPublished): void
    {
        $chapter = new BookChapter();
        $content = (new BookContent())
            ->setContent('testing')
            ->setIsPublished($onlyPublished)
            ->setChapter($chapter);

        $this->setEntityId($content, 1);

        $this->bookContentRepository->expects($this->once())
            ->method('getPageByChapterId')
            ->with(1, $onlyPublished, 0, self::PER_PAGE)
            ->willReturn(new \ArrayIterator([$content]));

        $this->bookContentRepository->expects($this->once())
            ->method('countByChapterId')
            ->with(1, $onlyPublished)
            ->willReturn(1);

        $service = $this->createService();
        $result = $onlyPublished
            ? $service->getPublishedContent(1, 1)
            : $service->getAllContent(1, 1);

        $expected = (new BookChapterContentPage())
            ->setTotal(1)
            ->setPages(1)
            ->setPage(1)
            ->setPerPage(self::PER_PAGE)
            ->setItems([
                (new BookChapterContent())->setContent('testing')->setIsPublished($onlyPublished)->setId(1),
            ]);

        $this->assertEquals($expected, $result);
    }

    private function createService(): BookContentService
    {
        return new BookContentService($this->bookContentRepository, $this->bookChapterRepository);
    }
}
