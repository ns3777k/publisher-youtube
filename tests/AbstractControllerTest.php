<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractControllerTest extends WebTestCase
{
    use JsonAssertions;

    protected KernelBrowser $client;

    protected ?EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->em = self::getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }
}
