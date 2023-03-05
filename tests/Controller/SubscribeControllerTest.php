<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use Symfony\Component\HttpFoundation\Response;

class SubscribeControllerTest extends AbstractControllerTest
{
    public function testSubscribe(): void
    {
        $content = json_encode(['email' => 'test@test.com', 'agreed' => true]);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);

        $this->assertResponseIsSuccessful();
    }

    public function testSubscribeNotAgreed(): void
    {
        $content = json_encode(['email' => 'test@test.com']);
        $this->client->request('POST', '/api/v1/subscribe', [], [], [], $content);
        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonDocumentMatches($responseContent, [
            '$.message' => 'validation failed',
            '$.details.violations' => self::countOf(1),
            '$.details.violations[0].field' => 'agreed',
        ]);
    }
}
