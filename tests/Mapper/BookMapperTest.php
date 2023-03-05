<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;

class BookMapperTest extends AbstractTestCase
{
    public function testMap(): void
    {
        $book = (new Book())->setTitle('title')->setSlug('slug')->setImage('123')
            ->setAuthors(['tester'])
            ->setPublicationDate(new \DateTimeImmutable('2020-10-10'));

        $this->setEntityId($book, 1);

        $expected = (new BookDetails())->setId(1)->setSlug('slug')->setTitle('title')
            ->setImage('123')->setAuthors(['tester'])
            ->setPublicationDate(1_602_288_000);

        $details = new BookDetails();

        BookMapper::map($book, $details);

        $this->assertEquals($expected, $details);
    }
}
