<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Entity\BookContent;
use App\Model\Author\CreateBookChapterContentRequest;
use App\Model\BookChapterContent;
use App\Model\BookChapterContentPage;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookContentRepository;

class BookContentService
{
    private const PAGE_LIMIT = 30;

    public function __construct(
        private readonly BookContentRepository $bookContentRepository,
        private readonly BookChapterRepository $bookChapterRepository)
    {
    }

    public function createContent(CreateBookChapterContentRequest $request, int $chapterId): IdResponse
    {
        $content = new BookContent();
        $content->setChapter($this->bookChapterRepository->getById($chapterId));

        $this->saveContent($request, $content);

        return new IdResponse($content->getId());
    }

    public function deleteContent(int $id): void
    {
        $content = $this->bookContentRepository->getById($id);

        $this->bookContentRepository->removeAndCommit($content);
    }

    public function updateContent(CreateBookChapterContentRequest $request, int $id): void
    {
        $content = $this->bookContentRepository->getById($id);

        $this->saveContent($request, $content);
    }

    public function getAllContent(int $chapterId, int $page): BookChapterContentPage
    {
        return $this->getContent($chapterId, $page, false);
    }

    public function getPublishedContent(int $chapterId, int $page): BookChapterContentPage
    {
        return $this->getContent($chapterId, $page, true);
    }

    private function getContent(int $chapterId, int $page, bool $onlyPublished): BookChapterContentPage
    {
        $items = [];
        $paginator = $this->bookContentRepository->getPageByChapterId(
            $chapterId,
            $onlyPublished,
            PaginationUtils::calcOffset($page, self::PAGE_LIMIT),
            self::PAGE_LIMIT
        );

        foreach ($paginator as $item) {
            $items[] = (new BookChapterContent())
                ->setId($item->getId())
                ->setContent($item->getContent())
                ->setIsPublished($item->isPublished());
        }

        $total = $this->bookContentRepository->countByChapterId($chapterId, $onlyPublished);

        return (new BookChapterContentPage())
            ->setTotal($total)
            ->setPage($page)
            ->setPerPage(self::PAGE_LIMIT)
            ->setPages(PaginationUtils::calcPages($total, self::PAGE_LIMIT))
            ->setItems($items);
    }

    private function saveContent(CreateBookChapterContentRequest $request, BookContent $content): void
    {
        $content->setContent($request->getContent());
        $content->setIsPublished($request->isPublished());

        $this->bookContentRepository->saveAndCommit($content);
    }
}
