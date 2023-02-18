<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookCategoryServiceTest extends AbstractTestCase
{
    public function testGetCategories(): void
    {
        $category = (new BookCategory())->setTitle('Test')->setSlug('test');
        $this->setEntityId($category, 7);

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([$category]);

        $slugger = $this->createMock(SluggerInterface::class);

        $service = new BookCategoryService($repository, $slugger);
        $expected = new BookCategoryListResponse([new BookCategoryModel(7, 'Test', 'test')]);

        $this->assertEquals($expected, $service->getCategories());
    }
}
