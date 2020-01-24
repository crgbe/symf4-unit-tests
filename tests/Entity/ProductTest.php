<?php


namespace App\Tests\Entity;


use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testComputeTVAFoodProduct(){
        $product = new Product('Product', 'food', 20);
        $result = $product->computeTVA();

        $this->assertSame(1.1, $result);
    }

    public function testComputeTVAOtherProduct(){
        $product = new Product('Product', 'others', 10);
        $result = $product->computeTVA();

        $this->assertSame(1.96, $result);
    }

    public function testComputeTVANotNegative(){
        $product = new Product('Product', 'any-food', -12);

        $this->expectException('LogicException');

        $product->computeTVA();
    }
}