<?php

namespace App\Repository;

use App\Entity\BookFormat;
use App\Exception\BookFormatNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookFormat|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookFormat|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookFormat[]    findAll()
 * @method BookFormat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookFormatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookFormat::class);
    }

    public function getById(int $id): BookFormat
    {
        $format = $this->find($id);
        if (null === $format) {
            throw new BookFormatNotFoundException();
        }

        return $format;
    }
}
