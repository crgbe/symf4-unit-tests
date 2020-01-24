<?php


namespace App\Tests\Entity;


use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    /**
     * @dataProvider getPricesAndExpectedProductTVAForFood
     * @param float $price
     * @param float $expectedTVA
     */
    public function testComputeTVAFoodProduct($price, $expectedTVA){
        $product = new Product('Product', Product::FOOD_PRODUCT, $price);

        $this->assertSame($expectedTVA, $product->computeTVA());
    }

//    public function testComputeTVAOtherProduct(){
//        $product = new Product('Product', 'others', 10);
//        $result = $product->computeTVA();
//
//        $this->assertSame(1.96, $result);
//    }
//
//    public function testComputeTVANotNegative(){
//        $product = new Product('Product', 'any-food', -12);
//
//        $this->expectException('LogicException');
//
//        $product->computeTVA();
//    }

    public function getPricesAndExpectedProductTVAForFood(){
        return [
            [10, 0.55],
            [20, 1.1],
            [15, 0.825],
            [45, 2.475],
        ];
    }
}