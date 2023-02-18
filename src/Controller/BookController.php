<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Controller;

use App\Model\BookDetails;
use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private BookService $bookService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns published books inside a category",
     *     @Model(type=BookListResponse::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="book category not found",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/category/{id}/books', methods: ['GET'])]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns published book detail information",
     *     @Model(type=BookDetails::class)
     * )
     * @OA\Response(
     *     response=404,
     *     description="book not found",
     *     @Model(type=ErrorResponse::class)
     * )
     */
    #[Route(path: '/api/v1/book/{id}', methods: ['GET'])]
    public function bookById(int $id): Response
    {
        return $this->json($this->bookService->getBookById($id));
    }
}
