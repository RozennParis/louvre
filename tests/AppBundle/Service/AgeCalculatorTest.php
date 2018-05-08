<?php
namespace Tests\AppBundle\Service;


use AppBundle\Service\AgeCalculator;
use PHPUnit\Framework\TestCase;

class AgeCalculatorTest extends TestCase
{
    /**
     * @dataProvider datesForCalculation
     */
    public function testAgeCalcul($visitDate, $birthDate, $expectedAge)
    {
        $age = new AgeCalculator();

        $this->assertEquals($expectedAge, $age->ageCalcul($visitDate, $birthDate));
    }

    public function datesForCalculation()
    {
        return [
            [new \DateTime('2018-06-01'), new \DateTime('1980-06-01'), 38],
            [new \DateTime('2018-06-01'), new \DateTime('2005-09-16'), 12],
            [new \DateTime('2018-06-01'), new \DateTime('2010-01-27'), 8],
            [new \DateTime('2018-06-01'), new \DateTime('2016-11-03'), 1],
            [new \DateTime('2018-06-01'), new \DateTime('1957-04-27'), 61]
        ];
    }
}
