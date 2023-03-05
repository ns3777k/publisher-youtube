<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Entity\BookCategory;
use App\Exception\BookCategoryAlreadyExistsException;
use App\Exception\BookCategoryNotEmptyException;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Model\BookCategoryUpdateRequest;
use App\Model\IdResponse;
use App\Repository\BookCategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class BookCategoryService
{
    public function __construct(
        private readonly BookCategoryRepository $bookCategoryRepository,
        private readonly SluggerInterface $slugger)
    {
    }

    public function deleteCategory(int $id): void
    {
        $category = $this->bookCategoryRepository->getById($id);
        $booksCount = $this->bookCategoryRepository->countBooksInCategory($category->getId());
        if ($booksCount > 0) {
            throw new BookCategoryNotEmptyException($booksCount);
        }

        $this->bookCategoryRepository->removeAndCommit($category);
    }

    public function createCategory(BookCategoryUpdateRequest $updateRequest): IdResponse
    {
        $category = new BookCategory();

        $this->upsertCategory($category, $updateRequest);

        return new IdResponse($category->getId());
    }

    public function updateCategory(int $id, BookCategoryUpdateRequest $updateRequest): void
    {
        $this->upsertCategory($this->bookCategoryRepository->getById($id), $updateRequest);
    }

    public function getCategories(): BookCategoryListResponse
    {
        $categories = $this->bookCategoryRepository->findAllSortedByTitle();
        $items = array_map(
            fn (BookCategory $bookCategory) => new BookCategoryModel(
                $bookCategory->getId(), $bookCategory->getTitle(), $bookCategory->getSlug()
            ),
            $categories
        );

        return new BookCategoryListResponse($items);
    }

    private function upsertCategory(BookCategory $category, BookCategoryUpdateRequest $updateRequest): void
    {
        $slug = $this->slugger->slug($updateRequest->getTitle());
        if ($this->bookCategoryRepository->existsBySlug($slug->toString())) {
            throw new BookCategoryAlreadyExistsException();
        }

        $category->setTitle($updateRequest->getTitle())->setSlug($slug->toString());

        $this->bookCategoryRepository->saveAndCommit($category);
    }
}
