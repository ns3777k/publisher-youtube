<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Tests\AbstractControllerTest;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;

class RecommendationControllerTest extends AbstractControllerTest
{
    private HoverflyClient $hoverfly;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpHoverfly();
    }

    public function testRecommendationsByBookId(): void
    {
        $id = $this->createBook();
        $requestedId = 123;

        $this->hoverfly->simulate(
            $this->hoverfly->buildSimulation()
                ->service()
                ->get(new RequestFieldMatcher(
                    '/api/v1/book/'.$requestedId.'/recommendations',
                    RequestFieldMatcher::GLOB
                ))
                ->headerExact('Authorization', 'Bearer test')
                ->willReturn(Response::json([
                    'ts' => 12345,
                    'id' => $requestedId,
                    'recommendations' => [['id' => $id]],
                ]))
        );

        $this->client->request('GET', '/api/v1/book/123/recommendations');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'shortDescription'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'shortDescription' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createBook(): int
    {
        $book = (new Book())
            ->setTitle('Test book')
            ->setImage('http://localhost.png')
            ->setMeap(true)
            ->setIsbn('123321')
            ->setDescription('test')
            ->setPublicationDate(new DateTimeImmutable())
            ->setAuthors(['Tester'])
            ->setCategories(new ArrayCollection([]))
            ->setSlug('test-book');

        $this->em->persist($book);
        $this->em->flush();

        return $book->getId();
    }

    private function setUpHoverfly(): void
    {
        $this->hoverfly = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverfly->deleteJournal();
        $this->hoverfly->deleteSimulation();
    }
}
