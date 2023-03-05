<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;

class SubscriberService
{
    public function __construct(private readonly SubscriberRepository $subscriberRepository)
    {
    }

    public function subscribe(SubscriberRequest $request): void
    {
        if ($this->subscriberRepository->existsByEmail($request->getEmail())) {
            throw new SubscriberAlreadyExistsException();
        }

        $this->subscriberRepository->saveAndCommit((new Subscriber())->setEmail($request->getEmail()));
    }
}
