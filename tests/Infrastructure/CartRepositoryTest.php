<?php

namespace Simara\Cart\Infrastructure;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartDetail;
use Simara\Cart\Domain\CartNotFoundException;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\ItemDetail;
use Simara\Cart\Domain\Price;

abstract class CartRepositoryTest extends TestCase
{
    /**
     * @var CartRepository
     */
    private $repository;

    public function testAddAndGetSuccessfully()
    {
        $cart = $this->createCartWithItem('1');
        $this->repository->add($cart);
        $this->flush();

        $foundCart = $this->repository->get('1');
        Assert::assertEquals($this->getCartDetailWithItem(), $foundCart->calculate());
    }

    private function createCartWithItem(string $id): Cart
    {
        $cart = new Cart($id);
        $cart->add('1', new Price(10), 1);
        return $cart;
    }

    protected function flush()
    {
    }

    private function getCartDetailWithItem(): CartDetail
    {
        $item = new ItemDetail('1', new Price(10), 1);
        return new CartDetail([$item], new Price(10));
    }

    public function testAddAndRemoveSuccessfully()
    {
        $cart = $this->createCartWithItem('1');
        $this->repository->add($cart);
        $this->flush();

        $this->repository->remove('1');
        $this->flush();

        $this->expectException(CartNotFoundException::class);
        $this->repository->get('1');
    }

    public function testAddedIsTheSameObject()
    {
        $empty = $this->createEmptyCart('1');
        $this->repository->add($empty);
        $empty->add('1', new Price(10.0));
        $this->flush();

        $found = $this->repository->get('1');
        Assert::assertEquals($this->getCartDetailWithItem(), $found->calculate());
    }

    public function testFlushChangedPersists()
    {
        $empty = $this->createEmptyCart('1');
        $this->repository->add($empty);
        $this->flush();

        $foundEmpty = $this->repository->get('1');
        $foundEmpty->add('1', new Price(10.0));
        $this->flush();

        $found = $this->repository->get('1');
        Assert::assertEquals($this->getCartDetailWithItem(), $found->calculate());
    }

    private function createEmptyCart(string $id): Cart
    {
        return new Cart($id);
    }

    private function getEmptyCartDetail(): CartDetail
    {
        return new CartDetail([], new Price(0));
    }

    public function testGetNotExistingCauseException()
    {
        $this->expectException(CartNotFoundException::class);

        $this->repository->get('1');
    }

    public function testRemoveNotExistingCauseException()
    {
        $this->expectException(CartNotFoundException::class);

        $this->repository->remove('1');
    }

    public function testAddTwoAndGetTwoSuccessfully()
    {
        $withItem = $this->createCartWithItem('1');
        $this->repository->add($withItem);
        $empty = $this->createEmptyCart('2');
        $this->repository->add($empty);
        $this->flush();

        $foundEmpty = $this->repository->get('1');
        Assert::assertEquals($this->getCartDetailWithItem(), $foundEmpty->calculate());

        $foundEmpty = $this->repository->get('2');
        Assert::assertEquals($this->getEmptyCartDetail(), $foundEmpty->calculate());
    }

    protected function setUp()
    {
        $this->repository = $this->createRepository();
    }

    abstract protected function createRepository(): CartRepository;
}
