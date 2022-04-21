<?php

declare(strict_types=1);

namespace App\DTO;

final class UserOutput
{
    public string $name;
    public string $email;
    public \DateTimeInterface $createdAt;
    public bool $enabled;
}
