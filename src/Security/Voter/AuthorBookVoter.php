<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Repository\BookRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AuthorBookVoter extends Voter
{
    public const IS_AUTHOR = 'IS_AUTHOR';

    public function __construct(private BookRepository $bookRepository)
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
