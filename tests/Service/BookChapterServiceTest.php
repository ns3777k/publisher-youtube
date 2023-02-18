<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Repository\BookChapterRepository;
use App\Service\BookChapterService;
use App\Tests\AbstractTestCase;

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
        $this->markTestSkipped();
    }

    private function createService(): BookChapterService
    {
        return new BookChapterService($this->bookChapterRepository);
    }
}
