<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractRepositoryTest extends KernelTestCase
{
    protected ?EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function getRepositoryForEntity(string $entityClass): mixed
    {
        return $this->em->getRepository($entityClass);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
