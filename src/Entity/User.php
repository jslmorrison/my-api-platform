<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class User
{
    private Uuid $id;
    private string $name;
    private string $email;
    private \DateTimeInterface $createdAt;
    private bool $enabled = false;

    private function __construct()
    {
    }

    public static function namedWithEmail(string $name, string $email)
    {
        $user = new self();
        $user->name = $name;
        $user->email = $email;

        $user->id = Uuid::v4();
        $user->createdAt = new \DateTimeImmutable();

        return $user;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }
}
