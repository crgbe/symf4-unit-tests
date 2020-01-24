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
}