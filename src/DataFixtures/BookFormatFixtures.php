<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BookFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFormatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $format1 = (new BookFormat())
            ->setTitle('eBook')
            ->setDescription('Our eBooks come in DRM-free Kindle, ePub, and PDF formats + liveBook, our enhanced eBook format accessible from any web browser.')
            ->setComment(null);

        $format2 = (new BookFormat())
            ->setTitle('print + eBook')
            ->setDescription('Receive a print copy shipped to your door + the eBook in Kindle, ePub, & PDF formats + liveBook, our enhanced eBook format accessible from any web browser.')
            ->setComment('FREE domestic shipping on orders of three or more print books');

        $manager->persist($format1);
        $manager->persist($format2);
        $manager->flush();
    }
}
