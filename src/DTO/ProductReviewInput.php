<?php

declare(strict_types=1);

namespace App\DTO;

final class ProductReviewInput
{
    public string $user = '';
    public string $product = '';
    public string $review = '';
}
