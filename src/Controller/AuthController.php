<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(private readonly SignUpService $signUpService)
    {
    }

    #[Route(path: '/api/v1/auth/signUp', methods: ['POST'])]
    #[OA\Response(response: 200, description: 'Signs up a user',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: 'token', type: 'string'),
            new OA\Property(property: 'refresh_token', type: 'string')])
    )]
    #[OA\Response(response: 409, description: 'User already exists', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: SignUpRequest::class)])]
    public function signUp(#[RequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->signUpService->signUp($signUpRequest);
    }
}
