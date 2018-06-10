<?php
namespace Tests\AppBundle\Service;


use AppBundle\Service\Tarificator;
use PHPUnit\Framework\TestCase;

/**
 * Class TarificatorTest
 * @package Tests\AppBundle\Service
 */
class TarificatorTest extends TestCase
{
    private $price;

    /**
     * @param $reduceRate
     * @param $age
     * @param $typeOfTicket
     * @param $expectedPrice
     * @dataProvider dataForPrice
     */
    public function testPriceOfTicket($reduceRate, $age, $typeOfTicket, $expectedPrice)
    {
        $price = new Tarificator();
        $this->assertSame($expectedPrice, $price->priceOfTicket($reduceRate, $age, $typeOfTicket));
    }

    /**
     * @return array
     */
    public function dataForPrice()
    {
        return [
            [false, 38, true, 16],
            [false, 38, false, 8],
            [false, 5, true, 8],
            [false, 5, false, 4],
            [false, 3, true, 0],
            [false, 3, false, 0],
            [false, 61, true, 12],
            [false, 61, false, 6],
            [true, 38, true, 10],
            [true, 38, false, 5],
            [true, 5, true, 8],
            [true, 5, false, 4],
            [true, 3, true, 0],
            [true, 3, false, 0],
            [true, 61, true, 10],
            [true, 61, false, 5]
        ];
    }

    /**
     * @dataProvider invalidArgument
     */
    public function testPriceOfTicketThrowningException($reduceRate, $age, $typeOfTicket)
    {
        $price = new Tarificator();
        $this->expectException(\InvalidArgumentException::class);

        $price->priceOfTicket($reduceRate, $age, $typeOfTicket);
    }

    /**
     * @return array
     */
    public function invalidArgument()
    {
        return [
            [12, 12, false],
            [12, 'hello', false],
            [12, 12, 68],
            [12, 'hello', 12],
            [false, 12, 12],
            [false, 'hello', 12],
            [false, 'hello', false]
        ];
    }

}
