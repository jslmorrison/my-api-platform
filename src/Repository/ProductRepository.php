<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;

interface ProductRepository
{
    public function add(Product $entity, bool $flush = true): void;
    public function remove(Product $entity, bool $flush = true): void;
}