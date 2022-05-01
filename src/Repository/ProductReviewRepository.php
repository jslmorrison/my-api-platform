<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductReview;

interface ProductReviewRepository
{
    public function add(ProductReview $entity, bool $flush = true): void;
    public function remove(ProductReview $entity, bool $flush = true): void;
}