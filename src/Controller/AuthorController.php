<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Model\Author\BookDetails;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookChapterRequest;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UpdateBookChapterRequest;
use App\Model\Author\UpdateBookChapterSortRequest;
use App\Model\Author\UpdateBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\BookChapterTreeResponse;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Security\Voter\AuthorBookVoter;
use App\Service\AuthorBookChapterService;
use App\Service\AuthorBookService;
use App\Service\BookPublishService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorBookService $authorService,
        private readonly BookPublishService $bookPublishService,
        private readonly AuthorBookChapterService $bookChapterService)
    {
    }

    #[Route(path: '/api/v1/author/book/{id}/uploadCover', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Upload book cover', attachables: [new Model(type: UploadCoverResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    public function uploadCover(
        int $id,
        #[RequestFile(field: 'cover', constraints: [
            new NotNull(),
            new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),
        ])] UploadedFile $file
    ): Response {
        return $this->json($this->authorService->uploadCover($id, $file));
    }

    #[Route(path: '/api/v1/author/book/{id}/publish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Publish a book')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: PublishBookRequest::class)])]
    public function publish(int $id, #[RequestBody] PublishBookRequest $request): Response
    {
        $this->bookPublishService->publish($id, $request);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{id}/unpublish', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Unpublish a book')]
    public function unpublish(int $id): Response
    {
        $this->bookPublishService->unpublish($id);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/books', methods: ['GET'])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Get authors owned books', attachables: [new Model(type: BookListResponse::class)])]
    public function books(#[CurrentUser] UserInterface $user): Response
    {
        return $this->json($this->authorService->getBooks($user));
    }

    #[Route(path: '/api/v1/author/book', methods: ['POST'])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Create a book', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookRequest::class)])]
    public function createBook(#[RequestBody] CreateBookRequest $request, #[CurrentUser] UserInterface $user): Response
    {
        return $this->json($this->authorService->createBook($request, $user));
    }

    #[Route(path: '/api/v1/author/book/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Remove a book')]
    #[OA\Response(response: 404, description: 'book not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{id}', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Update a book')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookRequest::class)])]
    public function updateBook(int $id, #[RequestBody] UpdateBookRequest $request): Response
    {
        $this->authorService->updateBook($id, $request);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{id}', methods: ['GET'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'id')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Get authors owned book', attachables: [new Model(type: BookDetails::class)])]
    #[OA\Response(response: 404, description: 'book not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function book(int $id): Response
    {
        return $this->json($this->authorService->getBook($id));
    }

    #[Route(path: '/api/v1/author/book/{bookId}/chapter', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Create a book chapter', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookChapterRequest::class)])]
    public function createBookChapter(#[RequestBody] CreateBookChapterRequest $request, int $bookId): Response
    {
        return $this->json($this->bookChapterService->createChapter($request, $bookId));
    }

    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{id}/sort', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Sort a book chapter')]
    #[OA\Response(response: 404, description: 'book chapter not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookChapterSortRequest::class)])]
    public function updateBookChapterSort(#[RequestBody] UpdateBookChapterSortRequest $request, int $bookId, int $id): Response
    {
        $this->bookChapterService->updateChapterSort($request, $id);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{id}', methods: ['POST'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Update a book chapter')]
    #[OA\Response(response: 404, description: 'book chapter not found', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: UpdateBookChapterRequest::class)])]
    public function updateBookChapter(#[RequestBody] UpdateBookChapterRequest $request, int $bookId, int $id): Response
    {
        $this->bookChapterService->updateChapter($request, $id);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{bookId}/chapters', methods: ['GET'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Get book chapters as tree', attachables: [new Model(type: BookChapterTreeResponse::class)])]
    public function chapters(int $bookId): Response
    {
        return $this->json($this->bookChapterService->getChaptersTree($bookId));
    }

    #[Route(path: '/api/v1/author/book/{bookId}/chapter/{id}', methods: ['DELETE'])]
    #[IsGranted(AuthorBookVoter::IS_AUTHOR, subject: 'bookId')]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Remove a book chapter')]
    #[OA\Response(response: 404, description: 'book chapter not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function deleteBookChapter(int $id, int $bookId): Response
    {
        $this->bookChapterService->deleteChapter($id);

        return $this->json(null);
    }
}
