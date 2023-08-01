<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Repository;

use App\Entity\BookContent;
use App\Exception\BookChapterContentNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookContent[]    findAll()
 * @method BookContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookContentRepository extends ServiceEntityRepository
{
    use RepositoryModifyTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookContent::class);
    }

    public function getById(int $id): BookContent
    {
        $chapter = $this->find($id);
        if (null === $chapter) {
            throw new BookChapterContentNotFoundException();
        }

        return $chapter;
    }

    /**
     * @return \Traversable&\Countable
     */
    public function getPageByChapterId(int $id, bool $onlyPublished, int $offset, int $limit)
    {
        $query = implode(' ', array_filter([
            'SELECT b FROM App\Entity\BookContent b WHERE b.chapter = :id',
            $onlyPublished ? 'AND b.isPublished = true' : null,
            'ORDER BY b.id ASC',
        ]));

        $query = $this->_em
            ->createQuery($query)
            ->setParameter('id', $id)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($query, false);
    }

    public function countByChapterId(int $id, bool $onlyPublished): int
    {
        $condition = ['chapter' => $id];
        if ($onlyPublished) {
            $condition['isPublished'] = true;
        }

        return $this->count($condition);
    }
}
