<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscriber[]    findAll()
 * @method Subscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(['email' => $email]);
    }
}
