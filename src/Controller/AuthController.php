<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Model\ErrorResponse;

class AuthController extends AbstractController
{
    public function __construct(private SignUpService $signUpService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Signs up a user",
     *     @OA\JsonContent(
     *         @OA\Property(property="token", type="string"),
     *         @OA\Property(property="refresh_token", type="string")
     *     )
     * )
     * @OA\Response(
     *     response="409",
     *     description="User already exists",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response="400",
     *     description="Validation failed",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(@Model(type=SignUpRequest::class))
     */
    #[Route(path: '/api/v1/auth/signUp', methods: ['POST'])]
    public function signUp(#[RequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->signUpService->signUp($signUpRequest);
    }
}
