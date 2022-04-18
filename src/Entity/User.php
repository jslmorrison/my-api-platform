<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: false, unique: true)]
    private string $email;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'boolean', nullable: false)]
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
