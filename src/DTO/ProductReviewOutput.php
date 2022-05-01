<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Product;
use App\Entity\User;
use DateTimeInterface;

final class ProductReviewOutput
{
    public User $user;
    public Product $product;
    public string $review;
    public DateTimeInterface $createdAt;
    public bool $active;
}
