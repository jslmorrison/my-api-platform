<?php

namespace spec\App\Entity;

use App\Entity\Product;
use PhpSpec\ObjectBehavior;

class ProductSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Product::class);
    }

    function let()
    {
        $this->beConstructedThrough('named', ['Product 1']);
    }

    function it_should_be_identifiable()
    {
        $this->id()->shouldBeAnInstanceOf(\Symfony\Component\Uid\Uuid::class);
    }

    function it_should_have_a_name()
    {
        $this->name()->shouldReturn('Product 1');
    }
}
