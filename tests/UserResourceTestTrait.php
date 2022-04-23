<?php

namespace App\Tests;

use App\Entity\User;

trait UserResourceTestTrait
{
    protected static $kernel;
    private $user;

    public function createUser(
        string $name,
        string $email
    ): User {
        $userRepository = self::$kernel->getContainer()
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository(User::class);
        $user = User::namedWithEmail($name, $email);

        $userRepository->add($user);

        return $user;
    }
}