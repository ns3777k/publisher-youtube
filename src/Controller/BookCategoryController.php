<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Controller;

use App\Model\BookCategoryListResponse;
use App\Service\BookCategoryService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(private readonly BookCategoryService $bookCategoryService)
    {
    }

    #[Route(path: '/api/v1/book/categories', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns book categories', attachables: [new Model(type: BookCategoryListResponse::class)])]
    public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}
