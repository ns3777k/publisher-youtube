<?php

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findPublishedBooksByCategoryId(int $id): array
    {
        return $this->_em
            ->createQuery('SELECT b FROM App\Entity\Book b WHERE :categoryId MEMBER OF b.categories AND b.publicationDate IS NOT NULL')
            ->setParameter('id', $id)
            ->getResult();
    }

    public function getPublishedById(int $id): Book
    {
        $book = $this->_em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.id = :id AND b.publicationDate IS NOT NULL')
            ->setParameter('id', $id)
            ->getOneOrNullResult();

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    /**
     * @return Book[]
     */
    public function findBooksByIds(array $ids): array
    {
        return $this->_em->createQuery('SELECT b FROM App\Entity\Book b WHERE b.id IN (:ids) AND b.publicationDate IS NOT NULL')
            ->setParameter('ids', $ids)
            ->getResult();
    }

    /**
     * @return Book[]
     */
    public function findUserBooks(UserInterface $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function getUserBookById(int $id, UserInterface $user): Book
    {
        $book = $this->findOneBy(['id' => $id, 'user' => $user]);
        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function existsBySlug(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }
}
