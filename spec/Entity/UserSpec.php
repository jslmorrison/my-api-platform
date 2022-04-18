<?php

namespace spec\App\Entity;

use App\Entity\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function let()
    {
        $this->beConstructedThrough('namedWithEmail', ['Test User', 'test@example.com']);
    }

    function it_should_be_identifiable()
    {
        $this->id()->shouldBeAnInstanceOf(\Symfony\Component\Uid\Uuid::class);
    }

    function it_should_have_a_name()
    {
        $this->name()->shouldReturn('Test User');
    }

    function it_should_have_an_email()
    {
        $this->email()->shouldReturn('test@example.com');
    }

    function it_should_know_when_created()
    {
        $this->createdAt()->shouldBeAnInstanceOf(\DateTimeInterface::class);
    }

    function it_should_not_be_enabled_by_default()
    {
        $this->isEnabled()->shouldBe(false);
    }

    function it_should_be_able_to_enable_itself()
    {
        $this->isEnabled()->shouldBe(false);
        $this->enable();
        $this->isEnabled()->shouldBe(true);
    }
}
