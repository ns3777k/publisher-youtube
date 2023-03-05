<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Security\Voter;

use App\Repository\BookRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AuthorBookVoter extends Voter
{
    final public const IS_AUTHOR = 'IS_AUTHOR';

    public function __construct(private readonly BookRepository $bookRepository)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (self::IS_AUTHOR !== $attribute) {
            return false;
        }

        return intval($subject) > 0;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $this->bookRepository->existsUserBookById((int) $subject, $token->getUser());
    }
}
