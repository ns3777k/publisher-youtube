<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Repository\ReviewRepository;

class RatingService
{
    public function __construct(private readonly ReviewRepository $reviewRepository)
    {
    }

    public function calcReviewRatingForBook(int $id): Rating
    {
        $total = $this->reviewRepository->countByBookId($id);
        $rating = $total > 0 ? $this->reviewRepository->getBookTotalRatingSum($id) / $total : 0;

        return new Rating($total, $rating);
    }
}
