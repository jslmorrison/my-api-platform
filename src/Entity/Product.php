<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductDoctrineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductDoctrineRepository::class)]
#[ORM\Table(name: '`products`')]
#[ApiResource()]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
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
}
