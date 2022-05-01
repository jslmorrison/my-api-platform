<?php

namespace spec\App\Entity;

use App\Entity\Product;
use App\Entity\ProductReview;
use App\Entity\User;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Uid\Uuid;

class ProductReviewSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ProductReview::class);
    }

    function let(Product $product, User $user)
    {
        $this->beConstructedThrough('forProductByUser', [$product, $user, 'the product review']);
    }

    function it_should_be_identifiable()
    {
        $this->id()->shouldBeAnInstanceOf(Uuid::class);
    }

    function it_should_be_for_a_product()
    {
        $this->product()->shouldBeAnInstanceOf(Product::class);
    }

    function it_should_be_by_a_user()
    {
        $this->user()->shouldBeAnInstanceOf(User::class);
    }

    function it_should_have_a_review()
    {
        $this->review()->shouldReturn('the product review');
    }

    function it_should_know_when_created()
    {
        $this->createdAt()->shouldBeAnInstanceOf(\DateTimeInterface::class);
    }

    function it_should_not_be_active_by_default()
    {
        $this->isActive()->shouldBe(false);
    }
}
