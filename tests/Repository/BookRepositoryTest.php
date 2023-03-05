<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    public function testFindBooksByCategoryId()
    {
        $user = MockUtils::createUser();
        $this->em->persist($user);

        $devicesCategory = MockUtils::createBookCategory();
        $this->em->persist($devicesCategory);

        for ($i = 0; $i < 5; ++$i) {
            $book = MockUtils::createBook()->setUser($user)
                ->setTitle('device-'.$i)
                ->setCategories(new ArrayCollection([$devicesCategory]));

            $this->em->persist($book);
        }

        $this->em->flush();

        $this->assertCount(5, $this->bookRepository->findPublishedBooksByCategoryId($devicesCategory->getId()));
    }

    private function createBook(string $title, BookCategory $category): Book
    {
        return (new Book())
            ->setPublicationDate(new \DateTimeImmutable())
            ->setAuthors(['author'])
            ->setSlug($title)
            ->setDescription('test description')
            ->setIsbn('12321')
            ->setCategories(new ArrayCollection([$category]))
            ->setTitle($title)
            ->setImage('http://localhost/'.$title.'.png');
    }
}
