<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

final class ProductDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Product;
    }

    public function persist($data, array $context = [])
    {
        $this->productRepository->add($data);

        return $data;
    }

    public function remove($data, array $context = [])
    {
        $this->productRepository->remove($data);
    }
}
