<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Controller;

use App\Model\BookChapterContentPage;
use App\Model\BookDetails;
use App\Model\BookListResponse;
use App\Model\ErrorResponse;
use App\Service\BookContentService;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService,
        private readonly BookContentService $bookContentService)
    {
    }

    #[Route(path: '/api/v1/category/{id}/books', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns published books inside a category', attachables: [new Model(type: BookListResponse::class)])]
    #[OA\Response(response: 404, description: 'book category not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function booksByCategory(int $id): Response
    {
        return $this->json($this->bookService->getBooksByCategory($id));
    }

    #[Route(path: '/api/v1/book/{id}', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns published book detail information', attachables: [new Model(type: BookDetails::class)])]
    #[OA\Response(response: 404, description: 'book not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function bookById(int $id): Response
    {
        return $this->json($this->bookService->getBookById($id));
    }

    #[Route(path: '/api/v1/book/{id}/chapter/{chapterId}/content', methods: ['GET'])]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer'))]
    #[OA\Response(response: 200, description: 'Get book chapter content', attachables: [new Model(type: BookChapterContentPage::class)])]
    public function chapterContent(Request $request, int $chapterId, int $id): Response
    {
        return $this->json($this->bookContentService->getPublishedContent(
            $chapterId, (int) $request->query->get('page', 1)
        ));
    }
}
