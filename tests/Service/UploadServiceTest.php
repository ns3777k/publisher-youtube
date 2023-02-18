<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Service;

use App\Exception\UploadFileInvalidTypeException;
use App\Service\UploadService;
use App\Tests\AbstractTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadServiceTest extends AbstractTestCase
{
    private const UPLOAD_DIR = '/tmp';

    private Filesystem $fs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fs = $this->createMock(Filesystem::class);
    }

    public function testDeleteBookFile(): void
    {
        $this->fs->expects($this->once())
            ->method('remove')
            ->with('/tmp/book/1/test.jpg');

        $this->createService()->deleteBookFile(1, 'test.jpg');
    }

    public function testUploadBookFileInvalidExtension(): void
    {
        $this->expectException(UploadFileInvalidTypeException::class);

        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('guessExtension')
            ->willReturn(null);

        $this->createService()->uploadBookFile(1, $file);
    }

    public function testUploadBookFile(): void
    {
        $file = $this->createMock(UploadedFile::class);
        $file->expects($this->once())
            ->method('guessExtension')
            ->willReturn('jpg');

        $file->expects($this->once())
            ->method('move')
            ->with($this->equalTo('/tmp/book/1'), $this->callback(function (string $arg) {
                if (!str_ends_with($arg, '.jpg')) {
                    return false;
                }

                return Uuid::isValid(basename($arg, '.jpg'));
            }));

        $actualPath = pathinfo($this->createService()->uploadBookFile(1, $file));

        $this->assertEquals('/upload/book/1', $actualPath['dirname']);
        $this->assertEquals('jpg', $actualPath['extension']);
        $this->assertTrue(Uuid::isValid($actualPath['filename']));
    }

    private function createService(): UploadService
    {
        return new UploadService($this->fs, self::UPLOAD_DIR);
    }
}
