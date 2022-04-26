<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use App\Repository\UserRepository;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        $this->userRepository->add($data);

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->userRepository->remove($data);
    }
}
