<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

class ProductReview
{
    private Uuid $id;
    private Product $product;
    private User $user;
    private string $review;
    private \DateTimeInterface $createdAt;
    private bool $active;

    private function __construct()
    {
    }

    public static function forProductByUser(Product $product, User $user, string $review)
    {
        $productReview = new self();
        $productReview->id = Uuid::v4();
        $productReview->product = $product;
        $productReview->user = $user;
        $productReview->review = $review;
        $productReview->createdAt = new \DateTimeImmutable();
        $productReview->active = false;

        return $productReview;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function review(): string
    {
        return $this->review;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
