<?php

namespace App\Tests\Repository;

use App\Entity\BookCategory;
use App\Repository\BookCategoryRepository;
use App\Tests\AbstractRepositoryTest;

class BookCategoryRepositoryTest extends AbstractRepositoryTest
{
    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookCategoryRepository = $this->getRepositoryForEntity(BookCategory::class);
    }

    public function testFindAllSortedByTitle()
    {
        $devices = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $android = (new BookCategory())->setTitle('Android')->setSlug('android');
        $computer = (new BookCategory())->setTitle('Computer')->setSlug('computer');

        foreach ([$devices, $android, $computer] as $category) {
            $this->em->persist($category);
        }

        $this->em->flush();

        $titles = array_map(
            fn (BookCategory $bookCategory) => $bookCategory->getTitle(),
            $this->bookCategoryRepository->findAllSortedByTitle(),
        );

        $this->assertEquals(['Android', 'Computer', 'Devices'], $titles);
    }
}
