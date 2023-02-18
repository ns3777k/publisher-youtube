<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;

use App\Entity\User;
use App\Repository\BookRepository;
use App\Security\Voter\AuthorBookVoter;
use App\Tests\AbstractTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AuthorBookVoterTest extends AbstractTestCase
{
    private BookRepository $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
    }

    public function testVoteNotSupports(): void
    {
        $voter = new AuthorBookVoter($this->bookRepository);
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->never())->method('getUser');

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $voter->vote($token, 1, ['test']));
    }

    public function testVote(): void
    {
        $this->vote(true, VoterInterface::ACCESS_GRANTED);
    }

    public function testVoteRestrict(): void
    {
        $this->vote(false, VoterInterface::ACCESS_DENIED);
    }

    private function vote(bool $existsUserBookByIdResult, int $expectedAccess): void
    {
        $user = new User();
        $voter = new AuthorBookVoter($this->bookRepository);
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->bookRepository->expects($this->once())
            ->method('existsUserBookById')
            ->with(1, $user)
            ->willReturn($existsUserBookByIdResult);

        $this->assertEquals($expectedAccess, $voter->vote($token, 1, [AuthorBookVoter::IS_AUTHOR]));
    }
}
