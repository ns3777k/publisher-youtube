<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

use App\Repository\UserRepository;

class RoleService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function grantAdmin(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_ADMIN');
    }

    public function grantAuthor(int $userId): void
    {
        $this->grantRole($userId, 'ROLE_AUTHOR');
    }

    private function grantRole(int $userId, string $role): void
    {
        $user = $this->userRepository->getUser($userId);
        $user->setRoles([$role]);

        $this->userRepository->commit();
    }
}
