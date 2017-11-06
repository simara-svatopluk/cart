<?php

namespace Simara\Cart\Application;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartDetail;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\Price;
use Simara\Cart\Domain\PriceAdapter;
use Simara\Cart\Domain\PriceNotFoundException;

class CartServiceTest extends TestCase
{
    /**
     * @var CartService
     */
    private $service;

    /**
     * @var CartRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var PriceAdapter|PHPUnit_Framework_MockObject_MockObject
     */
    private $priceAdapter;

    public function testCreate()
    {
        $this->repositoryExpectAdd();

        $this->service->create('1');
    }

    private function repositoryExpectAdd()
    {
        $this->repository
            ->expects($this->once())
            ->method('add')
            ->with($this->isInstanceOf(Cart::class));
    }

    public function testAddProduct()
    {
        $cart = $this->createMock(Cart::class);
        $price = new Price(10);
        $cart->expects($this->once())
            ->method('add')
            ->with('2', $this->equalTo($price), 3);

        $this->repositoryExpectGet($cart);
        $this->priceAdapterExpectsGetUnitPrice('2', $price);

        $this->service->addProduct('1', '2', 3);
    }

    private function repositoryExpectGet(PHPUnit_Framework_MockObject_MockObject $cart)
    {
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->willReturn($cart);
    }

    private function priceAdapterExpectsGetUnitPrice(string $productId, Price $price)
    {
        $this->priceAdapter
            ->expects($this->once())
            ->method('getUnitPrice')
            ->with($productId)
            ->willReturn($price);
    }

    public function testChangeAmount()
    {
        $cart = $this->createMock(Cart::class);
        $cart->expects($this->once())
            ->method('changeAmount')
            ->with('2', 5);

        $this->repositoryExpectGet($cart);

        $this->service->changeAmount('1', '2', 5);
    }

    public function testRemoveProduct()
    {
        $cart = $this->createMock(Cart::class);
        $cart->expects($this->once())
            ->method('remove')
            ->with('2');

        $this->repositoryExpectGet($cart);

        $this->service->removeProduct('1', '2');
    }

    public function testRemove()
    {
        $this->repositoryExpectRemove('1');

        $this->service->remove('1');
    }

    private function repositoryExpectRemove(string $id)
    {
        $this->repository
            ->expects($this->once())
            ->method('remove')
            ->with($id);
    }

    public function testCalculate()
    {
        $detail = new CartDetail([], new Price(0));

        $cart = $this->createMock(Cart::class);
        $cart->expects($this->once())
            ->method('calculate')
            ->willReturn($detail);

        $this->repositoryExpectGet($cart);

        $actualDetail = $this->service->calculate('1');

        Assert::assertEquals($detail, $actualDetail);
    }

    public function testAddProductWithoutPriceCannotBeBought()
    {
        $this->priceAdapterExpectsGetUnitPriceThrowsException('2');

        $this->expectException(ProductCannotBeBoughtException::class);
        $this->service->addProduct('1', '2');
    }

    private function priceAdapterExpectsGetUnitPriceThrowsException(string $productId)
    {
        $this->priceAdapter
            ->expects($this->once())
            ->method('getUnitPrice')
            ->with($productId)
            ->willThrowException(new PriceNotFoundException());
    }

    protected function setUp()
    {
        $this->repository = $this->createMock(CartRepository::class);
        $this->priceAdapter = $this->createMock(PriceAdapter::class);
        $this->service = new CartService($this->priceAdapter, $this->repository);
    }
}
