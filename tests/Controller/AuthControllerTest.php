<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;

class AuthControllerTest extends AbstractControllerTest
{
    public function testSignUp(): void
    {
        $this->client->request('POST', '/api/v1/auth/signUp', [], [], [], json_encode([
            'firstName' => 'Vasya',
            'lastName' => 'Testov',
            'email' => 'test@test.com',
            'password' => '1234567854',
            'confirmPassword' => '1234567854',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['token', 'refresh_token'],
            'properties' => [
                'token' => ['type' => 'string'],
                'refresh_token' => ['type' => 'string'],
            ],
        ]);
    }
}
