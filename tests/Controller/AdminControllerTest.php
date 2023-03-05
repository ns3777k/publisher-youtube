<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;

class AdminControllerTest extends AbstractControllerTest
{
    public function testGrantAuthor(): void
    {
        $user = $this->createUser('user@test.com', 'testtest');

        $this->createAdminAndAuth('admin@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/grantAuthor/'.$user->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteCategory(): void
    {
        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('DELETE', '/api/v1/admin/bookCategory/'.$bookCategory->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testCreateCategory(): void
    {
        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/bookCategory', [], [], [], json_encode([
            'title' => 'Test Chapter',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    public function testUpdateCategory(): void
    {
        $bookCategory = MockUtils::createBookCategory();
        $this->em->persist($bookCategory);
        $this->em->flush();

        $this->createAdminAndAuth('user@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/admin/bookCategory/'.$bookCategory->getId(), [], [], [],
            json_encode(['title' => 'Test Chapter 2']));

        $this->assertResponseIsSuccessful();
    }
}
