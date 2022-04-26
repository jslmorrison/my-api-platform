<?php

declare(strict_types=1);

namespace App\Entity;

use App\DTO\ProductInput;
use Symfony\Component\Uid\Uuid;

class Product
{
    private Uuid $id;
    private string $name;

    private function __construct()
    {
    }

    public static function named(string $name): self
    {
        $product = new self();
        $product->id = Uuid::v4();
        $product->name = $name;

        return $product;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function updateFromDTO(ProductInput $dto): self
    {
        $this->name = $dto->name;

        return $this;
    }
}
