<?php
namespace AppBundle\Service;

use AppBundle\Service\AgeCalculator;
use PHPUnit\Runner\Exception;

/**
 * Class Tarificator
 * @package AppBundle\Service
 */
class Tarificator
{
    const ADULT_PRICE = 16;
    const CHILD_UNDER_4_PRICE = 0;
    const CHILD_PRICE = 8;
    const SENIOR_PRICE = 12;
    const REDUCE_PRICE = 10;

    /**
     * @param $reduceRate
     * @param $age
     * @param $typeOfTicket
     * @return float|int
     */
    public function priceOfTicket($reduceRate, $age, $typeOfTicket)
    {
        $price = 0;
        if (!is_bool($reduceRate) || !is_int($age) || !is_bool($typeOfTicket) )
        {
            throw new \InvalidArgumentException('The format of arguments is wrong');
        }

        if ($reduceRate && $age>= 12){
            $price = self::REDUCE_PRICE;
        }

        else{
            switch ($age) {
                case ($age>= 12 && $age< 60) :
                    $price = self::ADULT_PRICE;
                    break;
                case ($age< 4):
                    $price = self::CHILD_UNDER_4_PRICE;
                    break;
                case ($age>= 4 && $age< 12) :
                    $price = self::CHILD_PRICE;
                    break;
                case ($age>= 60) :
                    $price = self::SENIOR_PRICE;
                    break;
            }
        }

        if ($typeOfTicket === false )
        {
            $price = $price/2;
        }
        return $price;
    }
}
